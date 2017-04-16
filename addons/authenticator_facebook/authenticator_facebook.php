<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Facebook;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Facebook extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Facebook',
			'icon'    => 'fa-facebook',
			'color'   => '#3b5998',
			'help'    => 'https://developers.facebook.com/',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return array_merge(parent::config(), [
			'scope' => ['public_profile', 'email']
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
