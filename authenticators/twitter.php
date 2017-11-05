<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_twitter extends Authenticator
{
	public $title = 'Twitter';
	public $color = '#1da1f2';
	public $icon  = 'fa-twitter';
	public $help  = 'https://apps.twitter.com/app/new';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['email']
		];
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
