<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax_Checker extends Controller_Module
{
	public function sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_teams')->where('team_id', $check['id'])->row())
		{
			return $check;
		}
	}

	public function _roles_sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_teams_roles')->where('role_id', $check['id'])->row())
		{
			return $check;
		}
	}
}
