<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Email extends Library
{
	protected $_key;
	protected $_from;
	protected $_reply_to;
	protected $_to = [];
	protected $_cc = [];
	protected $_bcc = [];
	protected $_subject;
	protected $_view;
	protected $_data;
	protected $_footer;
	protected $_attachments = [];
	protected $_config = [];

	public function __construct($caller, $config = [])
	{
		parent::__construct($caller);

		$this->_config = array_merge_recursive([
			'smtp' => []
		], $config);

		unset($this->_config['footer']);

		if (isset($config['footer']) && is_a($config['footer'], 'closure'))
		{
			$this->_footer = $config['footer'];
		}
		else
		{
			$this->_footer = function(){
				return $this->config->nf_description.' | <a href="'.url('//').'">'.$this->config->nf_name.'</a>';
			};
		}
	}

	public function __sleep()
	{
		return ['_key', '_from', '_reply_to', '_to', '_cc', '_bcc', '_subject'];
	}

	public function key(&$key = NULL)
	{
		if ($key === NULL)
		{
			$key = unique_id($this->db()->select('key')->from('nf_emailing_email_recipient')->get());
		}

		$this->_key = $key;

		return $this;
	}

	public function from($from, $name = '')
	{
		$this->_from = [$from];

		if ((string)$name !== '')
		{
			$this->_from[] = $name;
		}

		return $this;
	}

	public function to($to)
	{
		$this->_to[] = strtolower($to);

		return $this;
	}

	public function reply_to($to, $name = '')
	{
		$this->_reply_to = [strtolower($to)];

		if ((string)$name !== '')
		{
			$this->_reply_to[] = utf8_html_entity_decode($name);
		}

		return $this;
	}

	public function cc($to)
	{
		$this->_cc[] = strtolower($to);

		return $this;
	}

	public function bcc($to)
	{
		$this->_bcc[] = strtolower($to);

		return $this;
	}

	public function subject($subject)
	{
		$this->_subject = $subject;

		return $this;
	}

	public function message()
	{
		$args = func_get_args();

		$this->_data = array_pop($args);
		$this->_view = array_shift($args) ?: 'default';

		return $this;
	}

	public function attachment($file, $name = '')
	{
		if (is_a($file, 'NF\NeoFrag\Models\File'))
		{
			if ($name === '')
			{
				$name = utf8_html_entity_decode($file->name);
			}

			$file = $file->path;
		}

		if ($name === '')
		{
			$name = basename($file);
		}

		$this->_attachments[] = [$file, $name];

		return $this;
	}

	public function send()
	{
		if (!$this->_to || !$this->_subject || !$this->_view)
		{
			return FALSE;
		}

		require_once 'lib/phpmailer/PHPMailer.php';
		require_once 'lib/phpmailer/Exception.php';

		$debug = [];

		$PHPMailer = new \PHPMailer\PHPMailer\PHPMailer;

		$PHPMailer->SMTPDebug   = 2;
		$PHPMailer->Debugoutput = function($message) use (&$debug){
			$debug[] = $message;
		};

		if (!empty($this->_config['smtp']['host']))
		{
			require_once 'lib/phpmailer/SMTP.php';

			$PHPMailer->isSMTP();

			$PHPMailer->Host = $this->_config['smtp']['host'];

			if ($PHPMailer->SMTPAuth = $this->_config['smtp']['username'] && $this->_config['smtp']['password'])
			{
				$PHPMailer->Username = $this->_config['smtp']['username'];
				$PHPMailer->Password = $this->_config['smtp']['password'];
			}

			if ($this->_config['smtp']['secure'])
			{
				$PHPMailer->SMTPSecure = $this->_config['smtp']['secure'];
			}

			if ($this->_config['smtp']['port'])
			{
				$PHPMailer->Port = $this->_config['smtp']['port'];
			}
		}

		if ($this->_reply_to)
		{
			call_user_func_array([$PHPMailer, 'AddReplyTo'], $this->_reply_to);
		}

		$PHPMailer->setFrom(strtolower($this->_from && array_key_exists(0, $this->_from) ? $this->_from[0] : $this->config->nf_contact),
							utf8_html_entity_decode($this->_from && array_key_exists(1, $this->_from) ? $this->_from[1] : $this->config->nf_name),
							!ini_get('sendmail_from')
		);


		if ($this->_key)
		{
			$PHPMailer->MessageID = '<'.$this->_key.'@'.preg_replace('/^.+?@/', '', $PHPMailer->From).'>';
		}

		$PHPMailer->XMailer  = ' ';
		$PHPMailer->Encoding = 'quoted-printable';
		$PHPMailer->CharSet  = 'UTF-8';
		$PHPMailer->Subject  = utf8_html_entity_decode($this->_subject);
		$PHPMailer->isHTML(TRUE);

		foreach (array_unique($this->_to) as $to)
		{
			$PHPMailer->addAddress($to);
		}

		foreach (array_unique($this->_cc) as $to)
		{
			$mail->AddCC($to);
		}

		foreach (array_unique($this->_bcc) as $to)
		{
			$PHPMailer->AddBCC($to);
		}

		foreach ($this->_attachments as $attachment)
		{
			$PHPMailer->addAttachment(...$attachment);
		}

		$this->output->email(function() use ($PHPMailer){
			$data = array_merge([
				'header'  => '',
				'content' => '',
				'footer'  => ''
			], is_a($this->_data, 'closure') ? call_user_func($this->_data) : $this->_data);

			$data['footer'] .= call_user_func($this->_footer);

			$PHPMailer->Body = (string)$this->view('emails/main', [
				'body' => $this->view('emails/'.$this->_view, $data)
			]);

			$PHPMailer->AltBody = trim(strip_tags($PHPMailer->Body));
		});

		$sent = $PHPMailer->send();

		if (!$sent)
		{
			foreach ($debug as $message)
			{
				trigger_error(utf8_string($message, is_windows() ? 'CP1252' : ''), E_USER_WARNING);
			}
		}

		return $sent;
	}
}
