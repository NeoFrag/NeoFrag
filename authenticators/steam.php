<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class a_steam extends Authenticator
{
	protected $_keys = ['key'];

	public $title = 'Steam';
	public $color = '#171a21';
	public $icon  = 'fa-steam';
	public $help  = 'http://steamcommunity.com/dev/apikey';

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['key'],
			'applicationSecret' => ''
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['openid_sig']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->steamid,
					'username'      => $data->personaname,
					'email'         => '',
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => strtolower($data->loccountrycode),
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => $data->avatarfull
				];
			};
		}
	}

	protected function _params()
	{
		return [
			'domain' => $_SERVER['HTTP_HOST']
		];
	}
}
