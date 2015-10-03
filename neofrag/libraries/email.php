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
	private $_to;
	private $_subject;
	private $_view;
	private $_data;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_from = '"'.$this->config->nf_name.'" <'.$this->config->nf_contact.'>';
	}
	
	public function from($from)
	{
		$this->_from = $from;
		
		return $this;
	}
	
	public function to($to)
	{
		$this->_to = $to.', ';
		
		return $this;
	}
	
	public function subject($subject)
	{
		$this->_subject = $subject;
		
		return $this;
	}
	
	public function message($view, $data = array())
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
		
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
		{
			ini_set('sendmail_from', $this->_from);
		}
			
		$headers = array('From: '.$this->_from, 'Reply-to: '.$this->_from);
		
		$this->_data['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].url();

		$this->template->parse_data($this->_data, $this->load);
		
		$message = $html = $this->load->view('emails/'.$this->_view, $this->_data);
		$text            = $this->load->view('emails/'.$this->_view.'.txt', $this->_data);

		if ($text)
		{
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: multipart/alternative;'."\n".' boundary="'.($boundary = '--------'.unique_id()).'"';
			
			$message  = '--'.$boundary."\n";
			$message .= 'Content-Type: text/plain; charset=UTF-8; format=flowed'."\n\n";
			$message .= str_replace('\r', '', $text)."\n\n";
			
			$message .= '--'.$boundary."\n";
			$message .= 'Content-Type: text/html; charset=UTF-8;'."\n\n";
			$message .= str_replace('\r', '', $html)."\n\n";
		}

		$result = mail(trim_word($this->_to, ', '), $this->config->nf_name.' :: '.$this->_subject, wordwrap($message, 70), implode("\r\n", $headers));
		
		$this->reset();
		
		return $result;
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/libraries/email.php
*/