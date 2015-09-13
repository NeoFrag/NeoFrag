<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_access_c_admin_ajax_checker extends Controller_Module
{
	public function index()
	{
		return $this->_check_actions('action', 'module', 'type', 'id');
	}
	
	public function update()
	{
		$this->extension('json');
		
		list($action, $title, $icon, $module_name, $id) = $this->_check_actions('action', 'module', 'type', 'id');
		
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
			
			return array($module_name, $action, $id, $groups, array(), $title, $icon);
		}
		else if ($user = post('user'))
		{
			return array($module_name, $action, $id, array(), $user, $title, $icon);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function users()
	{
		return $this->_check_actions('action', 'module', 'type', 'id');
	}
	
	public function reset()
	{
		if (!array_diff($post = array('module', 'type', 'id'), array_keys($values = array_intersect_key(post(), $args = array_flip($post)))))
		{
			list($module_name, $type, $id) = array_values(array_merge($args, $values));
			
			$module = $this->load->module($module_name, FALSE);
			$permissions = $module->get_access($type);
			
			if (empty($permissions['check']) || call_user_func($permissions['check'], $id))
			{
				return array($module_name, $type, $id);
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	private function _check_actions()
	{
		if (!array_diff(func_get_args(), array_keys($values = array_intersect_key(post(), $args = array_flip(func_get_args())))))
		{
			list($action, $module_name, $type, $id) = array_values(array_merge($args, $values));
			
			$module = $this->load->module($module_name, FALSE);
			$permissions = $module->get_access($type);
			
			if (empty($permissions['check']) || call_user_func($permissions['check'], $id))
			{
				foreach ($permissions['access'] as $permissions)
				{
					if (isset($permissions['access'][$action]))
					{
						return array($action, $permissions['access'][$action]['title'], $permissions['access'][$action]['icon'], $module_name, $id);
					}
				}
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/access/controllers/admin_ajax_checker.php
*/