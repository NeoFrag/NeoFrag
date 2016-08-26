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

class Captcha extends Library
{
	public function is_ok()
	{
		return $this->config->nf_captcha_public_key && $this->config->nf_captcha_private_key;
	}
	
	public function is_valid()
	{
		if ($response = post('g-recaptcha-response'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?'.http_build_query([
				'secret'   => $this->config->nf_captcha_private_key,
				'response' => $response,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			]));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = json_decode(curl_exec($ch));
			curl_close($ch);

			return !empty($result->success);
		}
		
		return FALSE;
	}
	
	public function display()
	{
		return '<div class="g-recaptcha" data-sitekey="'.$this->config->nf_captcha_public_key.'"></div>';
	}
}

/*
NeoFrag Alpha 0.1.4.2
./neofrag/libraries/captcha.php
*/