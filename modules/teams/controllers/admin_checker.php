<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function add()
	{
		if (!$this->is_authorized('add_teams'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($team_id, $name)
	{
		if (!$this->is_authorized('modify_teams'))
		{
			$this->error->unauthorized();
		}

		if ($team = $this->model()->check_team($team_id, $name))
		{
			return $team;
		}
	}

	public function delete($team_id, $name)
	{
		if (!$this->is_authorized('delete_teams'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($team = $this->model()->check_team($team_id, $name))
		{
			return [$team['team_id'], $team['title']];
		}
	}

	public function _roles_add()
	{
		if (!$this->is_authorized('add_teams_roles'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _roles_edit($role_id, $name)
	{
		if (!$this->is_authorized('modify_teams_roles'))
		{
			$this->error->unauthorized();
		}

		if ($role = $this->model('roles')->check_role($role_id, $name))
		{
			return $role;
		}
	}

	public function _roles_delete($role_id, $name)
	{
		if (!$this->is_authorized('delete_teams_roles'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($role = $this->model('roles')->check_role($role_id, $name))
		{
			return $role;
		}
	}

	public function _players_delete($team_id, $name, $user_id)
	{
		$this->ajax();

		if (($team = $this->model()->check_team($team_id, $name)) && $user = $this->db->select('u.id as user_id', 'u.username')->from('nf_teams_users tu')->join('nf_user u', 'tu.user_id = u.id AND u.deleted = "0"', 'INNER')->where('tu.team_id', $team['team_id'])->where('tu.user_id', $user_id)->row())
		{
			return array_merge([$team['team_id']], $user);
		}
	}
}
