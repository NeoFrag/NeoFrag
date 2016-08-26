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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_access_c_admin_ajax_checker extends Controller_Module
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
			
			return [$module_name, $action, $id, $groups, [], $title, $icon];
		}
		else if ($user = post('user'))
		{
			return [$module_name, $action, $id, [], $user, $title, $icon];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function users()
	{
		return $this->_check_actions();
	}
	
	public function reset()
	{
		if (list($module_name, $type, $id) = array_values(post_check('module', 'type', 'id')))
		{
			$module = $this->load->module($module_name);
			
			if (($permissions = $module->get_permissions($type)) && (empty($permissions['check']) || call_user_func($permissions['check'], $id)))
			{
				return [$module_name, $type, $id];
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	private function _check_actions()
	{
		if (list($action, $module_name, $type, $id) = array_values(post_check('action', 'module', 'type', 'id')))
		{
			$module = $this->load->module($module_name);
			
			if (($permissions = $module->get_permissions($type)) && (empty($permissions['check']) || call_user_func($permissions['check'], $id)))
			{
				foreach ($permissions['access'] as $permissions)
				{
					if (isset($permissions['access'][$action]))
					{
						return [$action, $module->load->lang($permissions['access'][$action]['title'], NULL), $permissions['access'][$action]['icon'], $module_name, $id];
					}
				}
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/access/controllers/admin_ajax_checker.php
*/