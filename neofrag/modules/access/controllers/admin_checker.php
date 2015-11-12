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

class m_access_c_admin_checker extends Controller_Module
{
	public function index($tab = '', $page = '')
	{
		$modules = $objects = array();

		foreach ($this->addons->get_modules(TRUE) as $module)
		{
			foreach ($module->get_access() as $type => $access)
			{
				if (!empty($access['get_all']) && $get_all = call_user_func($access['get_all']))
				{
					$modules[$module->name] = array($module, $module->icon, $type, $access);
					$objects[$module->name] = $get_all;
				}
			}
		}

		uasort($modules, function($a, $b){
			return strnatcmp($a[0]->get_title(), $b[0]->get_title());
		});

		foreach ($modules as $module_name => $module)
		{
			if ($tab === '' || $module_name == $tab)
			{
				$objects = $objects[$module_name];
				
				foreach ($objects as &$object)
				{
					list($id, $title) = array_values($object);
					
					$object = array(
						'id'     => $id,
						'title'  => $module[0]->load->lang($title, NULL)
					);
					
					unset($object);
				}
				
				$tab = $module_name;
				break;
			}
		}

		return array($this->load->library('pagination')->get_data($objects, $page), $modules, $tab);
	}

	public function _edit($module_name, $access = '0-default')
	{
		$module = $this->load->module($module_name, FALSE);
		
		list($id, $type) = explode('-', $access);
		
		$access = $module->get_access($type);
		
		if (empty($access['check']) || $title = call_user_func($access['check'], $id))
		{
			return array($module, $type, $access['access'], $id, isset($title) ? $module->load->lang($title, NULL) : NULL);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/modules/access/controllers/admin_checker.php
*/