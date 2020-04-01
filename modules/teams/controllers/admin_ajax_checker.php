<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function sort()
	{
		if (($check = post_check('id', 'position')) && !$this->db->from('nf_teams')->where('team_id', $check['id'])->empty())
		{
			return $check;
		}
	}

	public function _roles_sort()
	{
		if (($check = post_check('id', 'position')) && !$this->db->from('nf_teams_roles')->where('role_id', $check['id'])->empty())
		{
			return $check;
		}
	}
}
