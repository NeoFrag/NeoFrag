<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Email extends Library
{
	protected $_from;
	protected $_to = [];
	protected $_subject;
	protected $_view;
	protected $_data;
	protected $_footer;

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
				return $this->config->nf_description.' | <a href="'.url().'">'.$this->config->nf_name.'</a>';
			};
		}
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

	public function send()
	{
		if (!$this->_to || !$this->_subject || !$this->_view)
		{
			return FALSE;
		}

		require 'lib/phpmailer/class.phpmailer.php';

		$mail = new \PHPMailer;

		if ($this->config->nf_email_smtp)
		{
			require 'lib/phpmailer/class.smtp.php';

			$mail->SMTPDebug = 1;
			$mail->Debugoutput = function($message){
				$this->debug('PHPMAILER', trim($message));
			};

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

		$mail->setFrom($this->config->nf_contact, $this->config->nf_name);

		$mail->CharSet = 'UTF-8';
		$mail->Subject = utf8_html_entity_decode($this->_subject);
		$mail->isHTML(TRUE);

		foreach (array_unique($this->_to) as $to)
		{
			$mail->addAddress($to);
		}

		$this->output->email(function() use ($mail){
			$data = [];

			if (is_a($this->_data, 'closure'))
			{
				$data = call_user_func($this->_data);
			}

			$data = array_merge([
				'header'  => '',
				'content' => '',
				'footer'  => ''
			], $data);

			$data['footer'] .= call_user_func($this->_footer);

			$mail->Body = (string)$this->view('emails/main', [
				'body' => $this->view('emails/'.$this->_view, $data)
			]);
		});

		return $mail->send();
	}
}
