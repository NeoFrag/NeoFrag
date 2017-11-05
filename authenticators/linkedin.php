<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_linkedin extends Authenticator
{
	public $title = 'LinkedIn';
	public $color = '#0077B5';
	public $icon  = 'fa-linkedin';
	public $help  = 'https://www.linkedin.com/secure/developer?newapp=';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret']
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
					'username'      => $data->firstName.$data->lastName,
					'email'         => $data->emailAddress,
					'first_name'    => $data->firstName,
					'last_name'     => $data->lastName,
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => $data->pictureUrl
				];
			};
		}
	}
}
