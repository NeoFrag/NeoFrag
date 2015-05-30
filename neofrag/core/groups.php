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

class Groups extends Core
{
	private $_groups = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$users = $this->db->select('user_id', 'admin')->from('nf_users')->get();
		
		$this->_groups = array(
			'admins' => array(
				'name'  => 'admins',
				'title' => $this->lang('admins'),
				'icon'  => 'fa-rocket',
				'users' => array_map('intval', array_map(function($a){return intval($a['user_id']);}, array_filter($users, function($a){return $a['admin'];}))),
				'auto'  => 'neofrag'
			),
			'members' => array(
				'name'  => 'members',
				'title' => $this->lang('members'),
				'icon'  => 'fa-user',
				'users' => array_map('intval', array_map(function($a){return intval($a['user_id']);}, array_filter($users, function($a){return !$a['admin'];}))),
				'auto'  => 'neofrag'
			),
			'visitors' => array(
				'name'  => 'visitors',
				'title' => $this->lang('visitors'),
				'icon'  => '',
				'users' => NULL,
				'auto'  => 'neofrag'
			)
		);
		
		$groups = $this->db	->select('g.group_id', 'g.name', 'g.color', 'g.icon', 'IFNULL(gl.title, g.name) AS title', 'GROUP_CONCAT(ug.user_id) AS users', 'g.auto')
							->from('nf_groups g')
							->join('nf_groups_lang gl', 'gl.group_id = g.group_id')
							->join('nf_users_groups ug', 'ug.group_id = g.group_id')
							->where('gl.lang', $this->config->lang, 'OR')
							->where('gl.lang', NULL)
							->group_by('g.group_id')
							->order_by('IFNULL(gl.title, g.name)')
							->get();

		foreach ($groups as $group)
		{
			$group_id = url_title($group['name']);
			
			if ($group['auto'])
			{
				if ($group['color'])
				{
					$this->_groups[$group_id]['color'] = $group['color'];
				}
				
				if ($group['icon'])
				{
					$this->_groups[$group_id]['icon'] = $group['icon'];
				}
				
				$this->_groups[$group_id]['id']   = $group['group_id'];
				$this->_groups[$group_id]['auto'] = TRUE;
			}
			else
			{
				$this->_groups[url_title($group['group_id'])] = array(
					'id'    => $group['group_id'],
					'name'  => $group['name'],
					'title' => $group['title'],
					'color' => $group['color'],
					'icon'  => $group['icon'],
					'users' => !empty($group['users']) ? array_map('intval', explode(',', $group['users'])) : array(),
					'auto'  => FALSE
				);
			}
		}

		foreach ($this->get_modules() as $module)
		{
			if (method_exists($module, 'groups'))
			{
				foreach ($module->groups() as $id => $group)
				{
					$group_id = url_title($module->get_name().'_'.$id);
					
					$this->_groups[$group_id]         = !empty($this->_groups[$group_id]) ? array_merge($group, $this->_groups[$group_id]) : $group;
					$this->_groups[$group_id]['auto'] = 'module_'.$module->get_name();
					
					if (empty($this->_groups[$group_id]['icon']))
					{
						$this->_groups[$group_id]['icon'] = $module->icon;
					}
				}
			}
		}

		uasort($this->_groups, function($a, $b){
			if ($a['auto'] == 'neofrag' && $b['auto'] == 'neofrag')
			{
				return strnatcmp($a['title'], $b['title']);
			}
			else if ($a['auto'] == 'neofrag')
			{
				return -1;
			}
			else if ($b['auto'] == 'neofrag')
			{
				return 1;
			}
			else if (($cmp = strnatcmp($a['auto'], $b['auto'])) != 0)
			{
				return $cmp;
			}
			
			return strnatcmp($a['title'], $b['title']);
		});
	}
	
	public function __invoke($user_id = NULL)
	{
		if (func_num_args() == 1)
		{
			$groups = array();
			
			foreach ($this->_groups as $group_id => $group)
			{
				if (!empty($group['users']) && in_array($user_id, $group['users']))
				{
					$groups[$group['id']] = $group_id;
				}
			}
			
			$groups = array_unique($groups);
			
			return empty($groups) ? array($this->_groups['visitors']['id']) : $groups;
		}
		else
		{
			return $this->_groups;
		}
	}

	public function user_groups($user_id, $label = TRUE)
	{
		$groups = array();
		
		foreach ($this->_groups as $group_id => $group)
		{
			if (!empty($group['users']) && in_array($user_id, $group['users']))
			{
				$groups[] = $this->display($group_id, $label);
			}
		}
		
		return implode(' ', $groups);
	}
	
	public function display($group_id, $label = TRUE, $link = TRUE)
	{
		if ($label)
		{
			if ($group_id == 'visitors')
			{
				$link = FALSE;
			}
			
			$class = !empty($this->_groups[$group_id]['color']) && in_array($this->_groups[$group_id]['color'], array('default', 'primary', 'success', 'info', 'warning', 'danger')) ? $this->_groups[$group_id]['color'] : 'default';
			return '<'.($link ? 'a href="{base_url}members/group/'.url_title($group_id).($this->_groups[$group_id]['auto'] != 'neofrag' ? '/'.url_title($this->_groups[$group_id]['title']) : '').'.html"' : 'span').' class="label label-'.$class.'"'.(!empty($this->_groups[$group_id]['color']) && $this->_groups[$group_id]['color'][0] == '#' ? ' style="background-color: '.$this->_groups[$group_id]['color'].'"' : '').'>'.(!empty($this->_groups[$group_id]['icon']) ? $this->assets->icon($this->_groups[$group_id]['icon']).'&nbsp;&nbsp;' : '').$this->_groups[$group_id]['title'].'</'.($link ? 'a' : 'span').'>';
		}
		else
		{
			return $this->_groups[$group_id]['title'];
		}
	}
	
	public function profiler()
	{

	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/groups.php
*/