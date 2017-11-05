<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_google extends Authenticator
{
	public $title = 'Google';
	public $color = '#db4437';
	public $icon  = 'fa-google';
	public $help  = 'https://console.developers.google.com/apis/credentials';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['email profile']
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
					'avatar'        => $data->picture,
				];
			};
		}
	}
}
