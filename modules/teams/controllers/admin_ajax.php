<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function sort($team_id, $position)
	{
		$teams = [];

		foreach ($this->db->select('team_id')->from('nf_teams')->where('team_id !=', $team_id)->order_by('order', 'team_id')->get() as $role)
		{
			$teams[] = $role;
		}

		foreach (array_merge(array_slice($teams, 0, $position, TRUE), [$team_id], array_slice($teams, $position, NULL, TRUE)) as $order => $team_id)
		{
			$this->db	->where('team_id', $team_id)
						->update('nf_teams', [
							'order' => $order
						]);
		}
	}

	public function _roles_sort($role_id, $position)
	{
		$roles = [];

		foreach ($this->db->select('role_id')->from('nf_teams_roles')->where('role_id !=', $role_id)->order_by('order', 'role_id')->get() as $role)
		{
			$roles[] = $role;
		}

		foreach (array_merge(array_slice($roles, 0, $position, TRUE), [$role_id], array_slice($roles, $position, NULL, TRUE)) as $order => $role_id)
		{
			$this->db	->where('role_id', $role_id)
						->update('nf_teams_roles', [
							'order' => $order
						]);
		}
	}
}
