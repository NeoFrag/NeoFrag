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

class m_monitoring_m_monitoring extends Model
{
	public $folders = ['backups', 'cache', 'config', 'lib', 'modules', 'neofrag', 'overrides', 'themes', 'upload', 'widgets'];

	public function get_info()
	{
		return [
			'php_server'       => 'PHP '.PHP_VERSION,
			'web_server'       => preg_match('#(.+?)/(.+?) #', $_SERVER['SERVER_SOFTWARE'], $match) ? $match[1].' '.$match[2] : $_SERVER['SERVER_SOFTWARE'],
			'databases_server' => $this->db->get_info('server').' '.$this->db->get_info('version'),
			'databases_innodb' => $this->db->get_info('innodb')
		];
	}
	
	public function check_server()
	{
		$server = $this->get_info();

		return [
			[
				'title' => $server['php_server'],
				'icon'  => 'fa-server',
				'check' => [
					'php_curl' => [
						'title' => 'cURL',
						'check' => function(&$errors){
							if (!extension_loaded('curl'))
							{
								$errors[] = ['L\'extension cURL doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					],
					'php_gd' => [
						'title' => 'GD',
						'check' => function(&$errors){
							if (!extension_loaded('gd'))
							{
								$errors[] = ['L\'extension GD doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					],
					'php_json' => [
						'title' => 'JSON',
						'check' => function(&$errors){
							if (!extension_loaded('json'))
							{
								$errors[] = ['L\'extension JSON doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					],
					'php_mbstring' => [
						'title' => 'mbstring',
						'check' => function(&$errors){
							if (!extension_loaded('mbstring'))
							{
								$errors[] = ['L\'extension mbstring doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					],
					'php_zip' => [
						'title' => 'Zip',
						'check' => function(&$errors){
							if (!extension_loaded('zip'))
							{
								$errors[] = ['L\'extension Zip doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					]
				]
			],
			[
				'title' => $server['web_server'],
				'icon'  => 'fa-globe',
				'check' => [
					'mod_rewrite' => [
						'title' => 'mod_rewrite',
						'check' => function(&$errors){
							if (!1)
							{
								$errors[] = ['L\'option de réécriture d\'URL doit être activée', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					]
				]
			],
			[
				'title' => $server['databases_server'],
				'icon'  => 'fa-database',
				'check' => [
					'innodb' => [
						'title' => 'InnoDB',
						'check' => function(&$errors) use ($server){
							if (!$server['databases_innodb'])
							{
								$errors[] = ['Le moteur de stockage InnoDB doit être activé', 'danger'];
								return FALSE;
							}
							
							return TRUE;
						}
					]
				]
			],
			[
				'title' => 'Envoi d\'email',
				'icon'  => 'fa-envelope-o',
				'check' => [
					'email' => [
						'title' => 'Test du serveur...',
						'check' => function(&$errors, &$title){
							if (!$this->email->to('test@neofr.ag')->subject('email_check')->message('default')->send())
							{
								$errors[] = ['Le serveur d\'envoi d\'email doit être configuré', 'danger'];
								$title = 'Échec';
								return FALSE;
							}
							
							$title = 'OK';
							return TRUE;
						}
					]
				]
			]
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/monitoring/models/monitoring.php
*/