<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Twitter;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Twitter extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Twitter',
			'icon'    => 'fa-twitter',
			'color'   => '#1da1f2',
			'help'    => 'https://apps.twitter.com/app/new',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return array_merge(parent::config(), [
			'scope' => ['email']
		]);
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['oauth_token']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->id,
					'username'      => $data->screen_name,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => $data->lang,
					'location'      => $data->location,
					'signature'     => $data->description,
					'website'       => $entities->url->urls[0]->display_url,
					'avatar'        => $data->profile_image_url_https
				];
			};
		}
	}
}
