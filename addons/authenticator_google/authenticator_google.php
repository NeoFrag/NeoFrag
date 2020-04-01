<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Google;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Google extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Google',
			'icon'    => 'fab fa-google',
			'color'   => '#db4437',
			'help'    => 'https://console.developers.google.com/apis/credentials',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return array_merge(parent::config(), [
			'scope' => ['profile']
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
					'avatar'   => $data->pictureURL
				];
			};
		}
	}
}
