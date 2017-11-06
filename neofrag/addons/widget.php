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

	protected $_output = '';

	static public function __class($name)
	{
		return 'Widgets\\'.$name.'\\'.$name;
	}

	static public function __label()
	{
		return ['Widgets', 'Widget', 'fa-cube', 'warning'];
	}

	public function __toString()
	{
		return $this->_output;
	}

	public function __actions()
	{
		return [
			['enable',   'Activer',       'fa-check',   'success'],
			['disable',  'Désactiver',    'fa-times',   'muted'],
			['settings', 'Configuration', 'fa-wrench',  'warning'],
			NULL,
			['reset',    'Réinitialiser', 'fa-refresh', 'danger'],
			['delete',   'Désinstaller',  'fa-remove',  'danger']
		];
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

		$this->_output = implode($this->get_output($type, $settings));

		return $this;
	}

	public function get_output($type, $settings = [])
	{
		if (($controller = $this->controller('index')) && $controller->has_method($type))
		{
			if (!is_array($output = call_user_func_array([$controller, $type], [$settings])))
			{
				$output = [$output];
			}

			return $output;
		}

		return [];
	}

	public function get_admin($type, $settings = [])
	{
		if (($controller = $this->controller('admin')) && $controller->has_method($type))
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
		if (($controller = $this->controller('checker')) && $controller->has_method($type))
		{
			return serialize(call_user_func_array([$controller, $type], [$settings]));
		}
	}
}
