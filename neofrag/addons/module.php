<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Addons;

use NF\NeoFrag\Loadables\Addon;

abstract class Module extends Addon
{
	static public $core = [
		'access'      => FALSE,
		'addons'      => FALSE,
		'admin'       => FALSE,
		'comments'    => TRUE,
		'live_editor' => FALSE,
		'monitoring'  => FALSE,
		'pages'       => TRUE,
		'search'      => TRUE,
		'settings'    => FALSE,
		'statistics'  => FALSE,
		'user'        => FALSE
	];

	static public function __class($name)
	{
		return 'Modules\\'.$name.'\\'.$name;
	}

	public function __toString()
	{
		return (string)$this->output->data->get('module', 'content');
	}

	public function __init()
	{

	}

	public function get_method(&$args, $ignore_ajax = FALSE)
	{
		$url = '';

		if ($this->url->admin)
		{
			$url .= 'admin';
		}

		if ($this->url->ajax && !$ignore_ajax)
		{
			$url .= '/ajax';
		}

		$url = ltrim($url, '/');

		$routes = $this->info()->routes;

		if ($url)
		{
			foreach (array_keys($routes) as $route)
			{
				if (!preg_match('#^'.$url.'#', $route))
				{
					unset($routes[$route]);
				}
			}

			$url .= '/';
		}

		$url .= implode('/', $args);

		$method = NULL;

		foreach ($routes as $route => $function)
		{
			if (preg_match('#^'.str_replace(array_map(function($a){ return '{'.$a.'}'; }, array_keys(self::$route_patterns)) + ['#'], array_values(self::$route_patterns) + ['\#'], $route).'$#', $url, $matches))
			{
				$args = [];

				if (in_string('{url_title*}', $route))
				{
					foreach (array_offset_left($matches) as $arg)
					{
						$args = array_merge($args, explode('/', trim($arg, '/')));
					}
				}
				else
				{
					$args = array_offset_left($matches);
				}

				$args = array_map(function($a){return trim($a, '/');}, $args);

				$method = $function;
				break;
			}
		}

		return $method;
	}

	public function get_permissions($type = NULL)
	{
		if (method_exists($this, 'permissions'))
		{
			$permissions = $this->permissions();

			if ($type === NULL)
			{
				return $permissions;
			}
			else if (isset($permissions[$type]))
			{
				return $permissions[$type];
			}
		}

		return [];
	}

	public function is_administrable(&$category = NULL)
	{
		if (property_exists($info = $this->info(), 'admin'))
		{
			$category = $info->admin;

			if (is_bool($category))
			{
				$category = $category ? 'default' : 'none';
			}

			return TRUE;
		}

		return FALSE;
	}

	public function is_authorized()
	{
		static $allowed = [];

		if (!array_key_exists($hash = spl_object_hash($this), $allowed))
		{
			$allowed[$hash] = FALSE;

			if ($this->is_administrable())
			{
				if ($this->user->admin)
				{
					$allowed[$hash] = TRUE;
				}
				else if (($all_permissions = $this->get_permissions('default')))
				{
					foreach ($all_permissions['access'] as $a)
					{
						foreach ($a['access'] as $action => $access)
						{
							if (!empty($access['admin']) && $this->access($this->info()->name, $action))
							{
								$allowed[$hash] = TRUE;
								break 2;
							}
						}
					}
				}
			}
		}

		return $allowed[$hash];
	}

	public function title($title)
	{
		$this->output->data->set('module', 'title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->output->data->set('module', 'subtitle', $subtitle);
		return $this;
	}

	public function icon($icon)
	{
		$this->output->data->set('module', 'icon', $icon);
		return $this;
	}

	public function add_action($url, $title = '', $icon = '')
	{
		if (!is_a($url, 'NF\NeoFrag\Libraries\Html'))
		{
			$url = $this->button($title, $icon, 'primary', $url);
		}

		$this->output->data->append('module', 'actions', $url);
		return $this;
	}

	public function ajax()
	{
		$this->output->data->set('module', 'ajax', TRUE);
		return $this;
	}

	public function api()
	{
		if ($controller = $this->controller('api'))
		{
			return NeoFrag()->___load('', 'api', [$controller]);
		}
	}
}
