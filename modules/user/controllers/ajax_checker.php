<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_c_ajax_checker extends Controller_Module
{
	public function _member($user_id, $username)
	{
		if ($user = $this->model()->check_user($user_id, $username))
		{
			return $user;
		}
	}
}
