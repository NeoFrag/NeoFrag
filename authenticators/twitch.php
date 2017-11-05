<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_twitch extends Authenticator
{
	public $title = 'Twitch';
	public $color = '#6441a4';
	public $icon  = 'fa-twitch';
	public $help  = 'https://www.twitch.tv/kraken/oauth2/clients/new';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['user_read']
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['code']) && !empty($_GET['state']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->_id,
					'username'      => $data->display_name,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => '',
					'signature'     => $data->bio,
					'website'       => '',
					'avatar'        => $data->logo
				];
			};
		}
	}
}
