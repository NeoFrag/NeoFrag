<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Steam;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Steam extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Steam',
			'icon'    => 'fab fa-steam',
			'color'   => '#171a21',
			'help'    => 'http://steamcommunity.com/dev/apikey',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public $_keys = ['key'];

	public function config()
	{
		return [
			'applicationId'     => $this->__settings->{$this->url->production() ? 'prod' : 'dev'}->key,
			'applicationSecret' => ''
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['openid_sig']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'       => $data->id,
					'username' => $data->username
				];
			};
		}
	}

	public function _params()
	{
		return [
			'domain' => $_SERVER['HTTP_HOST']
		];
	}
}
