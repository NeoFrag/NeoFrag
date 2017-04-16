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
			'icon'    => 'fa-google',
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
			'scope' => ['email profile']
		]);
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
					'avatar'        => $data->picture
				];
			};
		}
	}
}
