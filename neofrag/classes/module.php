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

abstract class Module extends Loadable
{
	static public $core = array(
		'access'      => FALSE,
		'addons'      => FALSE,
		'admin'       => FALSE,
		'comments'    => TRUE,
		'error'       => FALSE,
		'live_editor' => FALSE,
		'members'     => TRUE, 
		'pages'       => TRUE,
		'search'      => TRUE,
		'settings'    => FALSE,
		'user'        => FALSE
	);

	public $icon;
	public $routes = array();

	private $_output      = '';
	private $_actions     = array();

	public function paths()
	{
		return function(){
			if (!empty(NeoFrag::loader()->theme))
			{
				if (in_array($theme_name = NeoFrag::loader()->theme->name, array('default', 'admin')))
				{
					unset($theme_name);
				}
				else
				{
					unset($this->load->update);
				}
			}

			return array(
				'assets' => array(
					'assets',
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name : '',
					'overrides/modules/'.$this->name,
					'neofrag/modules/'.$this->name,
					'modules/'.$this->name
				),
				'controllers' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/controllers' : '',
					'overrides/modules/'.$this->name.'/controllers',
					'neofrag/modules/'.$this->name.'/controllers',
					'modules/'.$this->name.'/controllers'
				),
				'forms' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/forms' : '',
					'overrides/modules/'.$this->name.'/forms',
					'neofrag/modules/'.$this->name.'/forms',
					'modules/'.$this->name.'/forms'
				),
				'helpers' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/helpers' : '',
					'overrides/modules/'.$this->name.'/helpers',
					'neofrag/modules/'.$this->name.'/helpers',
					'modules/'.$this->name.'/helpers'
				),
				'lang' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/lang' : '',
					'overrides/modules/'.$this->name.'/lang',
					'neofrag/modules/'.$this->name.'/lang',
					'modules/'.$this->name.'/lang'
				),
				'libraries' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/libraries' : '',
					'overrides/modules/'.$this->name.'/libraries',
					'neofrag/modules/'.$this->name.'/libraries',
					'modules/'.$this->name.'/libraries'
				),
				'models' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/models' : '',
					'overrides/modules/'.$this->name.'/models',
					'neofrag/modules/'.$this->name.'/models',
					'modules/'.$this->name.'/models'
				),
				'views' => array(
					!empty($theme_name) ? 'themes/'.$theme_name.'/overrides/modules/'.$this->name.'/views' : '',
					'overrides/modules/'.$this->name.'/views',
					'neofrag/modules/'.$this->name.'/views',
					'modules/'.$this->name.'/views'
				)
			);
		};
	}

	public function append_output($output)
	{
		if (is_string($output))
		{
			$this->_output .= $output;
		}
		else
		{
			$this->_output = $output;
		}
	}

	public function get_output()
	{
		return ob_get_clean().($this->config->extension_url == 'json' ? (is_string($this->_output) ? $this->_output : json_encode($this->_output)) : display($this->_output));
	}

	public function add_action($url, $title, $icon = '')
	{
		$this->_actions[] = array($url, $title, $icon);
	}

	public function get_actions()
	{
		return $this->_actions;
	}

	public function get_method(&$args, $ignore_ajax = FALSE)
	{
		$url = '';

		if ($this->config->admin_url)
		{
			$url .= 'admin';
		}
		
		if ($this->config->ajax_url && !$ignore_ajax)
		{
			$url .= '/ajax';
		}
		
		$url = ltrim($url, '/');
		
		if ($url)
		{
			foreach (array_keys($this->routes) as $route)
			{
				if (!preg_match('#^'.$url.'#', $route))
				{
					unset($this->routes[$route]);
				}
			}
			
			$url .= '/';
		}
		
		$url .= implode('/', $args);

		$method = NULL;

		foreach ($this->routes as $route => $function)
		{
			if (preg_match('#^'.str_replace(array_map(function($a){ return '{'.$a.'}'; }, array_keys(self::$route_patterns)) + array('#'), array_values(self::$route_patterns) + array('\#'), $route).'$#', $url, $matches))
			{
				$args = array();
				
				if (in_string('{url_title*}', $route))
				{
					foreach (array_offset_left($matches) as $arg)
					{
						$args = array_merge($args, explode('/', $arg));
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
			$permissions = $this::permissions();
			
			if ($type === NULL)
			{
				return $permissions;
			}
			else if (isset($permissions[$type]))
			{
				return $permissions[$type];
			}
		}

		return array();
	}

	public function model($model = '')
	{
		return $this->load->model($model ?: $this->name);
	}
	
	public function is_administrable()
	{
		return ($controller = $this->load->controller('admin')) && (!isset($controller->administrable) || $controller->administrable);
	}

	public function is_authorized()
	{
		static $allowed;
		
		if ($allowed === NULL)
		{
			$allowed = FALSE;
			
			if ($controller = $this->load->controller('admin'))
			{
				if ($this->user('admin'))
				{
					$allowed = TRUE;
				}
				else if (isset($this->groups($this->user('user_id'))[1]))
				{
					if ($all_permissions = $this->get_permissions('default'))
					{
						foreach ($all_permissions['access'] as $a)
						{
							foreach ($a['access'] as $action => $access)
							{
								if (!empty($access['admin']) && $this->access($this->name, $action))
								{
									$allowed = TRUE;
									break 2;
								}
							}
						}
					}
				}
			}
		}
		
		return $allowed;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/module.php
*/