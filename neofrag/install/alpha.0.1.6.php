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

class i_0_1_6 extends Install
{
	public function up()
	{
		$default_settings = [
			'default_background'                 => [0, 'int'],
			'nf_team_logo '                      => [0, 'int'],
			'nf_http_authentication'             => [FALSE, 'bool'],
			'nf_http_authentication_name'        => ['', 'string'],
			'nf_maintenance'                     => [FALSE, 'bool'],
			'nf_maintenance_opening'             => ['', 'string'],
			'nf_maintenance_title'               => ['', 'string'],
			'nf_maintenance_content'             => ['', 'string'],
			'nf_maintenance_logo'                => [0, 'int'],
			'nf_maintenance_background'          => [0, 'int'],
			'nf_maintenance_background_repeat'   => ['', 'string'],
			'nf_maintenance_background_position' => ['', 'string'],
			'nf_maintenance_background_color'    => ['', 'string'],
			'nf_maintenance_text_color'          => ['', 'string'],
			'nf_maintenance_facebook'            => ['', 'string'],
			'nf_maintenance_twitter'             => ['', 'string'],
			'nf_maintenance_google-plus'         => ['', 'string'],
			'nf_maintenance_steam'               => ['', 'string'],
			'nf_maintenance_twitch'              => ['', 'string']
		];

		foreach ($default_settings as $name => $setting)
		{
			list($value, $type) = $setting;

			if (!isset($this->config->$name))
			{
				$this->config($name, $value, $type);
			}
		}

		$this->db->execute('ALTER TABLE `nf_users_profiles` CHANGE `date_of_birth` `date_of_birth` DATE NULL DEFAULT NULL');
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/install/alpha.0.1.6.php
*/