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
			'icon'    => 'fab fa-facebook-f',
			'color'   => '#3b5998',
			'help'    => 'https://developers.facebook.com/',
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
					'id'       => $data->id,
					'username' => $data->fullname
				];
			};
		}
	}
}
