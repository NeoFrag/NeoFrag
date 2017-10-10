<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax_Checker extends Controller_Module
{
	public function _member($user_id, $username)
	{
		if ($user = $this->model()->check_user($user_id, $username))
		{
			return $user;
		}
	}

	public function login()
	{
		$this->error_if($this->user());

		return [];
	}

	public function register()
	{
		$this->error_if($this->user());

		return [];
	}

	public function lost_password()
	{
		$this->error_if($this->user());

		return [];
	}

	public function _lost_password($token)
	{
		$this->error_if($this->user());

		if ($token = $this->model2('token', $token))
		{
			return [$token];
		}
	}
}
