<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_addons_c_admin_checker extends Controller_Module
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
