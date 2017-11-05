<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_battle_net extends Authenticator
{
	public $title = 'Battle.net';
	public $color = '#19547c';
	public $icon  = 'fa-bold';
	public $help  = 'https://dev.battle.net/apps/register';

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
