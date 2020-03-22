<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function help($id, $title, $method)
	{
		if (($addon = $this->_check_addon($id, $title)) && ($help_controller = $addon->addon()->controller('admin_help')) && $help_controller->has_method($method))
		{
			$this->ajax();
			return [$help_controller, $method];
		}
	}

	public function _action($action, $id, $title)
	{
		if (($addon = $this->_check_addon($id, $title)) && ($controller = $addon->controller()))
		{
			$actions = $controller->__actions();

			if (isset($actions[$action]) && (!isset($actions[$action][4]) || $actions[$action][4]($addon->addon())))
			{
				$this->ajax_if(!empty($actions[$action][3]));

				return [$addon->addon(), $controller, $action];
			}
		}
	}

	private function _check_addon($id, $title)
	{
		return NeoFrag()->model2('addon', $id)->check($title);
	}
}
