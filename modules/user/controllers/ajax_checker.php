<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Ajax_Checker extends Module_Checker
{
	public function _member($id, $username)
	{
		if (($user = $this->model2('user', $id)->check($username)) && !$user->deleted)
		{
			return [$user];
		}
	}

	public function login()
	{
		$this->error_if($this->user());

		return [];
	}

	public function register()
	{
		$this->error_if($this->user() || !$this->config->nf_registration_status);

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
