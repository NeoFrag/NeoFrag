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
	protected $_subject;
	protected $_view;
	protected $_data;
	protected $_footer;
	protected $_attachments = [];

	public function __construct($caller, $config = [])
	{
		parent::__construct($caller);

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

		require_once 'lib/phpmailer/class.phpmailer.php';

		$debug = [];

		$mail = new \PHPMailer;

		$mail->SMTPDebug   = 2;
		$mail->Debugoutput = function($message) use (&$debug){
			$debug[] = $message;
		};

		if ($this->config->nf_email_smtp)
		{
			require_once 'lib/phpmailer/class.smtp.php';

			$mail->isSMTP();

			$mail->Host = $this->config->nf_email_smtp;

			if ($mail->SMTPAuth = $this->config->nf_email_username && $this->config->nf_email_password)
			{
				$mail->Username = $this->config->nf_email_username;
				$mail->Password = $this->config->nf_email_password;
			}

			if ($this->config->nf_email_secure)
			{
				$mail->SMTPSecure = $this->config->nf_email_secure;
			}

			if ($this->config->nf_email_port)
			{
				$mail->Port = $this->config->nf_email_port;
			}
		}

		if ($this->_from)
		{
			$mail->AddReplyTo($this->_from);
		}

		$mail->setFrom($this->config->nf_contact, $this->config->nf_name, !ini_get('sendmail_from'));

		$mail->CharSet = 'UTF-8';
		$mail->Subject = utf8_html_entity_decode($this->_subject);
		$mail->isHTML(TRUE);

		foreach (array_unique($this->_to) as $to)
		{
			$mail->addAddress($to);
		}

		foreach ($this->_attachments as $file)
		{
			$mail->addAttachment($file->path, utf8_html_entity_decode($file->name));
		}

		$this->output->email(function() use ($mail){
			$data = array_merge([
				'header'  => '',
				'content' => '',
				'footer'  => ''
			], is_a($this->_data, 'closure') ? call_user_func($this->_data) : $this->_data);

			$data['footer'] .= call_user_func($this->_footer);

			$mail->Body = (string)$this->view('emails/main', [
				'body' => $this->view('emails/'.$this->_view, $data)
			]);

			$mail->AltBody = trim(strip_tags($mail->Body));
		});

		if (!($result = $mail->send() ? static::$_id : FALSE))
		{
			foreach ($debug as $message)
			{
				trigger_error($message, E_USER_WARNING);
			}
		}

		return $result;
	}
}
