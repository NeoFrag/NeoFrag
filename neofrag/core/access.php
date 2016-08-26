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

class Access extends Core
{
	private $_access = [];
	private $_users  = [];
	
	public function __construct()
	{
		parent::__construct();
		$this->reload();
	}
	
	public function reload()
	{
		$this->_access = [];
		
		foreach ($this->db	->select('ad.entity', 'ad.type', 'ad.authorized', 'a.action', 'a.id', 'a.module')
							->from('nf_access a')
							->join('nf_access_details ad', 'a.access_id = ad.access_id')
							->order_by('type <> "user"', 'authorized DESC')
							->get() as $access)
		{
			if ($access['entity'])
			{
				$this->_access[$access['module']][$access['action']][$access['id']][] = [
					'entity'     => $access['entity'],
					'type'       => $access['type'],
					'authorized' => (bool)$access['authorized'],
				];
			}
			else
			{
				$this->_access[$access['module']][$access['action']][$access['id']] = TRUE;
			}
		}
	}
	
	public function __invoke($module, $action, $id = 0, $group_id = NULL, $user_id = NULL)
	{
		$access = isset($this->_access[$module][$action][$id]) ? $this->_access[$module][$action][$id] : FALSE;

		if (	$group_id == 'admins' || 
				($user_id !== NULL && in_array('admins', $this->groups($user_id))) || 
				($group_id === NULL && $user_id === NULL && $this->user('admin')) ||
				$access === TRUE
			)
		{
			return TRUE;
		}
		else if (!$access)
		{
			//TODO return FALSE;
			return $action == 'module_access';
		}

		$authorized = array_fill(0, 2, 0);
		
		foreach ($access as $permission)
		{
			if ($permission['type'] == 'group')
			{
				$authorized[$permission['authorized']]++;
			}
		}
		
		$user_groups = $user_id || $this->user() ? $this->groups($user_id ?: $this->user('user_id')) : ['visitors'];
		$default     = array_sum($authorized) ? $authorized[0] > $authorized[1] : TRUE;
		$groups      = [];
		
		foreach ($access as $permission)
		{
			if ($permission['type'] == 'group' && $group_id === NULL && in_array($permission['entity'], $user_groups))
			{
				$groups[$permission['entity']] = (bool)$permission['authorized'];
			}
			else if ($permission['type'] == 'group' && $group_id == $permission['entity'])
			{
				return (bool)$permission['authorized'];
			}
			else if ($permission['type'] == 'user'  && $group_id === NULL && $permission['entity'] == ($user_id ?: $this->user('user_id')))
			{
				return (int)(bool)$permission['authorized'];
			}
		}
		
		if ($group_id !== NULL)
		{
			return $default;
		}

		if ($user_id || $this->user())
		{
			foreach (array_keys($this->groups()) as $group)
			{
				if (!isset($groups[$group]) && $group != 'admins')
				{
					$groups[$group] = $default;
				}
			}
			
			foreach (array_keys($groups) as $group)
			{
				if (!in_array($group, $user_groups))
				{
					unset($groups[$group]);
				}
			}
		}
		else
		{
			$groups = ['visitors' => isset($groups['visitors']) ? $groups['visitors'] : $default];
		}
		
		foreach (array_keys($groups) as $group)
		{
			foreach (array_keys($groups) as $group2)
			{
				if ($group != $group2)
				{
					if (array_intersect($this->_load_users($group2), $this->_load_users($group)) == $this->_load_users($group2))
					{
						unset($groups[$group]);
						continue 2;
					}
				}
			}
		}

		if (count($groups = array_unique($groups)) == 1)
		{
			return current($groups);
		}
	}
	
	public function count($module, $action, $id = 0, &$ambiguous = FALSE)
	{
		$count = array_fill(0, 2, 0);
		
		foreach ($this->db->select('user_id')->from('nf_users')->where('deleted', FALSE)->get() as $user_id)
		{
			$access    = $this($module, $action, $id, NULL, $user_id);
			$ambiguous = $ambiguous || $access === NULL;
			
			$count[(int)$access]++;
		}
		
		$output = [];
		
		if ($ambiguous)
		{
			$output[] = '<span class="text-danger" data-toggle="tooltip" title="'.$this->load->lang('ambiguities').'">'.icon('fa-warning').'</span>';
		}
		
		if (!empty($count[1]))
		{
			$output[] = '<span class="text-success" data-toggle="tooltip" title="'.$this->load->lang('authorized_members').'" data-original-title="">'.icon('fa-check').' '.$count[1].'</span>';
		}
		
		if (!empty($count[0]))
		{
			$output[] = '<span class="text-danger" data-toggle="tooltip" title="'.$this->load->lang('forbidden_members').'">'.icon('fa-ban').' '.$count[0].'</span>';
		}
		
		if (!$this($module, $action, $id, 'visitors'))
		{
			$output[] = '<span class="text-info" data-toggle="tooltip" title="'.$this->load->lang('forbidden_guests').'">'.icon('fa-eye-slash').'</span>';
		}
		
		return implode(str_repeat('&nbsp;', 3), $output);
	}

	public function init($module_name, $type = 'default', $id = 0)
	{
		$module = $this->load->module($module_name);
		$access = $module->get_permissions($type);
		
		if (!empty($access['init']))
		{
			foreach ($access['init'] as $action => $groups)
			{
				$access_id = $this->db->insert('nf_access', [
					'module' => $module_name,
					'action' => $action,
					'id'     => $id
				]);
				
				foreach ($groups as $group)
				{
					list($entity, $authorized) = $group;
					
					$this->db->insert('nf_access_details', [
						'access_id'  => $access_id,
						'entity'     => $entity,
						'type'       => 'group',
						'authorized' => $authorized
					]);
				}
			}
			
			$this->reload();
		}

		return $this;
	}
	
	public function delete($module, $id = 0)
	{
		$this->db	->where('module', $module)
					->where('id', $id)
					->delete('nf_access');

		return $this;
	}

	public function revoke($group_id)
	{
		$this->db	->where('entity', $group_id)
					->where('type', 'group')
					->delete('nf_access_details');

		return $this;
	}
	
	public function admin()
	{
		static $allowed;
		
		if ($allowed === NULL)
		{
			$allowed = FALSE;
			
			if ($this->user('admin'))
			{
				$allowed = TRUE;
			}
			else if (isset($this->groups($this->user('user_id'))[1]))
			{
				foreach ($this->addons->get_modules() as $module)
				{
					if ($module->is_authorized())
					{
						$allowed = TRUE;
						break;
					}
				}
			}
		}
		
		return $allowed;
	}

	private function _load_users($group_id)
	{
		if (!isset($this->_users[$group_id]))
		{
			$this->_users[$group_id] = $this->groups()[$group_id]['users'];
			
			if (!isset($this->_users['admins']))
			{
				$this->_users['admins'] = $this->groups()['admins']['users'];
			}
			
			$this->_users[$group_id] = array_diff($this->_users[$group_id], $this->_users['admins']);
		}
		
		return $this->_users[$group_id];
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/core/access.php
*/