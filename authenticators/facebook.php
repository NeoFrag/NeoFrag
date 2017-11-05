<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_facebook extends Authenticator
{
	public $title = 'Facebook';
	public $color = '#3b5998';
	public $icon  = 'fa-facebook';
	public $help  = 'https://developers.facebook.com/';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['public_profile', 'email']
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
