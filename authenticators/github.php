<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_github extends Authenticator
{
	public $title = 'GitHub';
	public $color = '#24292e';
	public $icon  = 'fa-github';
	public $help  = 'https://github.com/settings/applications/new';

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
