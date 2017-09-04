<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($tab = '', $page = '')
	{
		$modules = $objects = [];

		foreach ($this->addons->get_modules() as $module)
		{
			foreach ($module->get_permissions() as $type => $access)
			{
				if (!empty($access['get_all']) && $get_all = call_user_func($access['get_all']))
				{
					$modules[$module->name] = [$module, $module->icon, $type, $access];
					$objects[$module->name] = $get_all;
				}
			}
		}

		array_natsort($modules, function($a){
			return $a[0]->get_title();
		});

		foreach ($modules as $module_name => $module)
		{
			if ($tab === '' || $module_name == $tab)
			{
				$objects = $objects[$module_name];

				foreach ($objects as &$object)
				{
					list($id, $title) = array_values($object);

					$object = [
						'id'     => $id,
						'title'  => $module[0]->lang($title, NULL)
					];

					unset($object);
				}

				$tab = $module_name;
				break;
			}
		}

		return [$this->pagination->get_data($objects, $page), $modules, $tab];
	}

	public function _edit($module_name, $access = '0-default')
	{
		$module = $this->module($module_name);

		list($id, $type) = explode('-', $access);

		if (($access = $module->get_permissions($type)) && (empty($access['check']) || $title = call_user_func($access['check'], $id)))
		{
			return [$module, $type, $access['access'], $id, isset($title) ? $module->lang($title, NULL) : NULL];
		}
	}
}
