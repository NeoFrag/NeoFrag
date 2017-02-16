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

class a_google extends Authenticator
{
	public $title = 'Google';
	public $color = '#db4437';
	public $icon  = 'fa-google';
	public $help  = 'https://console.developers.google.com/apis/credentials';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['email profile']
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['code']) && !empty($_GET['state']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->id,
					'username'      => $data->given_name.$data->family_name,
					'email'         => $data->email,
					'first_name'    => $data->given_name,
					'last_name'     => $data->family_name,
					'date_of_birth' => '',
					'sex'           => isset($data->gender) ? $data->gender : '',
					'language'      => $data->locale,
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => $data->picture,
				];
			};
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./authenticators/google.php
*/