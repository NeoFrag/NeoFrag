<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function _module_settings($name)
	{
		if (($module = $this->module($name)) && method_exists($module, 'settings'))
		{
			return [$module];
		}
	}

	public function _module_delete($name)
	{
		$this->ajax();

		if (($module = $this->module($name)) && $module->is_removable())
		{
			return [$module];
		}
	}

	public function _theme_settings($name)
	{
		if (($theme = $this->theme($name)) && ($controller = $theme->controller('admin')) && $controller->has_method('index'))
		{
			return [$theme, $controller];
		}
	}

	public function _theme_delete($name)
	{
		$this->ajax();

		if (($theme = $this->theme($name)) && $theme->is_removable())
		{
			return [$theme];
		}
	}
}
