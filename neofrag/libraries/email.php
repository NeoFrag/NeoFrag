<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Email extends Library
{
	static protected $_id;

	protected $_from;
	protected $_to = [];
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

	public function tracking($action = '')
	{
		return '?__email='.static::$_id.($action ? '&__action='.$action : '');
	}

	public function from($from)
	{
		$this->_from = $from;

		return $this;
	}

	public function to($to)
	{
		$this->_to[] = strtolower($to);

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

	public function attachment($file)
	{
		$this->_attachments[] = $file;
		return $this;
	}

	public function send()
	{
		if (!$this->_to || !$this->_subject || !$this->_view)
		{
			return FALSE;
		}

		do
		{
			static::$_id = unique_id();
		}
		while ($this->module('newsletter') && $this->db()->select('1')->from('nf_newsletter_campaign_email')->where('id', static::$_id)->row());

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

		if ($this->_from)
		{
			$PHPMailer->AddReplyTo($this->_from);
		}

		$PHPMailer->setFrom($this->config->nf_contact, $this->config->nf_name, !ini_get('sendmail_from'));


		$PHPMailer->XMailer  = ' ';
		$PHPMailer->Encoding = 'quoted-printable';
		$PHPMailer->CharSet  = 'UTF-8';
		$PHPMailer->Subject  = utf8_html_entity_decode($this->_subject);
		$PHPMailer->isHTML(TRUE);

		foreach (array_unique($this->_to) as $to)
		{
			$PHPMailer->addAddress($to);
		}

		foreach (array_unique($this->_bcc) as $to)
		{
			$PHPMailer->AddBCC($to);
		}

		foreach ($this->_attachments as $file)
		{
			$PHPMailer->addAttachment($file->path, utf8_html_entity_decode($file->name));
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

		if (!($result = $PHPMailer->send() ? static::$_id : FALSE))
		{
			foreach ($debug as $message)
			{
				trigger_error($message, E_USER_WARNING);
			}
		}

		return $result;
	}
}
