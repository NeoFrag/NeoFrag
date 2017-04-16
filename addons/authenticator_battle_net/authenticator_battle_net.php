<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class A_Authenticator_Battle_Net extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Battle.net',
			'icon'    => 'fa-bold',
			'color'   => '#19547c',
			'help'    => 'https://dev.battle.net/apps/register',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret']
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
					'username'      => preg_replace('/#\d+$/', '', $data->battletag),
					'email'         => '',
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => ''
				];
			};
		}
	}
}
