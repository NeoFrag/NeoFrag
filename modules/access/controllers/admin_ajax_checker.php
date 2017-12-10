<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function index()
	{
		return $this->_check_actions();
	}

	public function update()
	{
		$this->extension('json');

		list($action, $title, $icon, $module_name, $id) = $this->_check_actions();

		if ($groups = post('groups'))
		{
			foreach ($all_groups = array_keys($this->groups()) as $group)
			{
				if (!isset($groups[$group]))
				{
					$groups[$group] = FALSE;
				}
			}

			foreach (array_keys($groups) as $group)
			{
				if (!in_array($group, $all_groups))
				{
					unset($groups[$group]);
				}
			}

			$groups['admins'] = TRUE;

			return [$module_name, $action, $id, array_map('intval', $groups), [], $title, $icon];
		}
		else if ($user = post('user'))
		{
			return [$module_name, $action, $id, [], $user, $title, $icon];
		}
	}

	public function users()
	{
		return $this->_check_actions();
	}

	public function reset()
	{
		if (list($module_name, $type, $id) = array_values(post_check('module', 'type', 'id')))
		{
			$module = $this->module($module_name);

			if (($permissions = $module->get_permissions($type)) && (empty($permissions['check']) || call_user_func($permissions['check'], $id)))
			{
				return [$module_name, $type, $id];
			}
		}
	}

	private function _check_actions()
	{
		if (list($action, $module_name, $type, $id) = array_values(post_check('action', 'module', 'type', 'id')))
		{
			$module = $this->module($module_name);

			if (($permissions = $module->get_permissions($type)) && (empty($permissions['check']) || call_user_func($permissions['check'], $id)))
			{
				foreach ($permissions['access'] as $permissions)
				{
					if (isset($permissions['access'][$action]))
					{
						return [$action, $permissions['access'][$action]['title'], $permissions['access'][$action]['icon'], $module_name, $id];
					}
				}
			}
		}
	}
}
