<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_members(), $page)];
	}

	public function _edit($user_id, $username)
	{
		if ($this->model()->check_user($user_id, $username))
		{
			return $this->model()->get_user_profile($user_id);
		}
	}

	public function delete($user_id, $username)
	{
		$this->ajax();

		if ($user = $this->model()->check_user($user_id, $username))
		{
			return $user;
		}
	}

	public function _groups_edit()
	{
		if ($group = $this->groups->check_group(func_get_args()))
		{
			return [
				isset($group['id']) ? $group['id'] : 0,
				$group['unique_id'],
				$group['title'],
				$group['color'],
				$group['icon'],
				$group['hidden'],
				$group['auto']
			];
		}
	}

	public function _groups_delete()
	{
		$this->ajax();

		if ($group = $this->groups->check_group(func_get_args()))
		{
			if (!$group['auto'])
			{
				return [$group['id'], $group['title']];
			}
		}
	}

	public function _sessions($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_sessions(), $page)];
	}

	public function _sessions_delete($session_id)
	{
		$this->ajax();

		if ($session = $this->model()->check_session($session_id))
		{
			return [$session['session_id'], $session['username']];
		}
	}
}
