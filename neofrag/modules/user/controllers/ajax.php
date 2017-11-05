<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_c_ajax extends Controller_Module
{
	public function _member($user_id, $username)
	{
		return $this->view('profile_mini', $this->model()->get_user_profile($user_id));
	}
}
