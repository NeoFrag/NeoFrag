<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function _action($action, $id, $title)
	{
		if (($addon = NeoFrag()->model2('addon', $id)->check(str_replace('-', '_', $title))) &&
			($controller = $addon->controller()))
		{
			$actions = $controller->__actions();

			if (isset($actions[$action]) && (!isset($actions[$action][4]) || $actions[$action][4]($addon->addon())))
			{
				$this->ajax_if(!empty($actions[$action][3]));

				return [$addon->addon(), $controller, $action];
			}
		}
	}
}
