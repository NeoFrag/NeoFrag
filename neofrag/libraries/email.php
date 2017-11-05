<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Email extends Library
{
	private $_from;
	private $_to = [];
	private $_subject;
	private $_view;
	private $_data;

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

	public function message($view, $data = [])
	{
		$this->_view = $view;
		$this->_data = $data;

		return $this;
	}

	public function send()
	{
		if (!$this->_to || !$this->_subject || !$this->_view)
		{
			return FALSE;
		}

		require 'lib/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer;

		if ($this->config->nf_email_smtp)
		{
			require 'lib/phpmailer/class.smtp.php';

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
			$mail->setFrom($this->_from);
		}
		else
		{
			$mail->setFrom($this->config->nf_contact, $this->config->nf_name);
		}

		$mail->CharSet = 'UTF-8';
		$mail->Subject = $this->_subject;
		$mail->isHTML(TRUE);

		foreach (array_unique($this->_to) as $to)
		{
			$mail->addAddress($to);
		}

		$this->url->external(TRUE);

		$this->output->parse_data($this->_data);

		$mail->Body    = $this->view('emails/'.$this->_view, $this->_data);
		$mail->AltBody = $this->view('emails/'.$this->_view.'.txt', $this->_data);

		$result = $mail->send();

		$this->url->external(FALSE);

		return $result;
	}
}
