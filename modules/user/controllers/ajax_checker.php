<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Ajax_Checker extends Module_Checker
{
	public function _member($user_id, $username)
	{
		if ($user = $this->model()->check_user($user_id, $username))
		{
			return $user;
		}
	}
}
