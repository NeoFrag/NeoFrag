<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

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
		
		$base_url = $this->config->base_url;
		$this->config->base_url = $this->config->host.$base_url;

		$this->template->parse_data($this->_data, $this->load);
		
		$mail->Body    = $this->load->view('emails/'.$this->_view, $this->_data);
		$mail->AltBody = $this->load->view('emails/'.$this->_view.'.txt', $this->_data);

		$result = $mail->send();

		$this->reset();

		$this->config->base_url = $base_url;

		return $result;
	}
}

/*
NeoFrag Alpha 0.1.4.2
./neofrag/libraries/email.php
*/