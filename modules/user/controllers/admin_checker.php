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
		return [NeoFrag()->collection('user')->paginate($page)];
	}

	public function edit($id, $username)
	{
		if ($user = NeoFrag()->model2('user', $id)->check($username))
		{
			return [$user];
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
		return [NeoFrag()->collection('session')->order_by('_.last_activity DESC')->paginate($page)];
	}

	public function _sessions_delete($session_id)
	{
		$this->ajax();

		if ($this->db->select('1')->from('nf_session')->where('id', $session_id)->row())
		{
			$this->ajax();

			return [$session_id];
		}
	}
}
