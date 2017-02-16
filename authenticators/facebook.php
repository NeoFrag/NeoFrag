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

class a_facebook extends Authenticator
{
	public $title = 'Facebook';
	public $color = '#3b5998';
	public $icon  = 'fa-facebook';
	public $help  = 'https://developers.facebook.com/';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['public_profile', 'email']
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
					'username'      => $data->first_name.$data->last_name,
					'email'         => $data->email,
					'first_name'    => $data->first_name,
					'last_name'     => $data->last_name,
					'date_of_birth' => '',
					'sex'           => $data->gender,
					'language'      => preg_replace('/^(.+?)_/', '\1', $data->locale),
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => ''
				];
			};
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./authenticators/facebook.php
*/