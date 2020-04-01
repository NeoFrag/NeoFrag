<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Twitch;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Twitch extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Twitch',
			'icon'    => 'fab fa-twitch',
			'color'   => '#6441a4',
			'help'    => 'https://www.twitch.tv/kraken/oauth2/clients/new',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return array_merge(parent::config(), [
			'scope' => ['user_read']
		]);
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['code']) && !empty($_GET['state']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'       => $data->id,
					'username' => $data->fullname,
					'avatar'   => $data->logo
				];
			};
		}
	}
}
