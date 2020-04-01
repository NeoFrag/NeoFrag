<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Models;

use NF\NeoFrag\Loadables\Model;

class Monitoring extends Model
{
	public $folders = ['addons', 'backups', 'cache', 'config', 'css', 'fonts', 'images', 'js', 'lib', 'logs', 'modules', 'neofrag', 'overrides', 'themes', 'upload', 'widgets'];

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
				'icon'  => 'fas fa-server',
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
				'icon'  => 'fas fa-globe',
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
				'icon'  => 'fas fa-database',
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
				'icon'  => 'far fa-envelope',
				'check' => [
					'email' => [
						'title' => 'Test du serveur...',
						'check' => function(&$errors, &$title){
							if (!$this->email->to('test@neofr.ag')->subject('email_check')->message('default', ['content' => ''])->send())
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
