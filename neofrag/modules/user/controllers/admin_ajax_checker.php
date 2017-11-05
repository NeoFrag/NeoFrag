<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_c_admin_ajax_checker extends Controller
{
	public function _groups_sort()
	{
		if (($check = post_check('id', 'position')) && ($group = $this->groups->check_group([$check['id']])) && $group['auto'] != 'neofrag')
		{
			return $check;
		}
	}
}
