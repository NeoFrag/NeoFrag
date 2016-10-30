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

class m_addons_c_admin_checker extends Controller_Module
{
	public function _module_settings($name)
	{
		if (($module = $this->load->module($name)) && method_exists($module, 'settings'))
		{
			return [$module];
		}
	}

	public function _module_delete($name)
	{
		$this->ajax();

		if (($module = $this->load->module($name)) && $module->is_removable())
		{
			return [$module];
		}
	}

	public function _theme_settings($name)
	{
		if (($theme = $this->load->theme($name)) && ($controller = $theme->load->controller('admin')) && method_exists($controller, 'index'))
		{
			return [$theme, $controller];
		}
	}

	public function _theme_delete($name)
	{
		$this->ajax();

		if (($theme = $this->load->theme($name)) && $theme->is_removable())
		{
			return [$theme];
		}
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/addons/controllers/admin_checker.php
*/