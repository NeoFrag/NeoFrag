<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Addons;

use NF\NeoFrag\Loadables\Addon;

abstract class Widget extends Addon
{
	static public $core = [
		'breadcrumb' => TRUE,
		'html'       => TRUE,
		'members'    => TRUE,
		'module'     => FALSE,
		'navigation' => TRUE,
		'user'       => TRUE
	];

	static public function __class($name)
	{
		return 'Widgets\\'.$name.'\\'.$name;
	}

	public function is_removable()
	{
		return !in_array($this->name, ['access', 'addons', 'admin', 'comments', 'live_editor', 'members', 'pages', 'search', 'settings', 'user']);
	}

	public function output($type = 'index', $settings = [])
	{
		if (is_array($type))
		{
			$settings = $type;
			$type     = 'index';
		}

		if (($controller = $this->controller('index')) && $controller->has_method($type))
		{
			return call_user_func_array([$controller, $type], [$settings]);
		}
	}

	public function get_admin($type, $settings = [])
	{
		if (($controller = @$this->controller('admin')) && $controller->has_method($type))
		{
			if (!is_array($output = call_user_func_array([$controller, $type], [$settings])))
			{
				$output = [$output];
			}

			return $output;
		}

		return [];
	}

	public function get_settings($type, $settings = [])
	{
		if (($controller = @$this->controller('checker')) && $controller->has_method($type))
		{
			return serialize(call_user_func_array([$controller, $type], [$settings]));
		}
	}
}
