<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Github;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Github extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'GitHub',
			'icon'    => 'fa-github',
			'color'   => '#24292e',
			'help'    => 'https://github.com/settings/applications/new',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
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
					'username'      => $data->login,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => $data->location,
					'signature'     => $data->bio,
					'website'       => stripslashes($data->blog),
					'avatar'        => stripslashes($data->avatar_url)
				];
			};
		}
	}
}
