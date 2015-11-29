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

abstract class Module extends Translatable
{
	static public $patterns = array(
		'id'         => '([0-9]+?)',
		'key_id'     => '([a-z0-9]+?)',
		'url_title'  => '([a-z0-9-]+?)',
		'url_title*' => '([a-z0-9-/]+?)',
		'page'       => '((?:/?page/[0-9]+?)?)',
		'pages'      => '((?:/?(?:all|page/[0-9]+?(?:/(?:10|25|50|100))?))?)'
	);
	
	private $_module_name;
	private $_output      = '';
	private $_actions     = array();

	public $title         = '';
	public $description   = '';
	public $icon          = '';
	public $link          = '';
	public $author        = '';
	public $licence       = '';
	public $version       = '';
	public $nf_version    = '';
	public $administrable = TRUE;
	public $deactivatable = TRUE;
	public $routes        = array();

	public $controllers   = array();
	public $segments      = array();

	public function __construct($module_name)
	{
		if (NeoFrag::loader()->theme)
		{
			if (in_array($theme_name = NeoFrag::loader()->theme->name, array('default', 'admin')))
			{
				unset($theme_name);
			}
		}
		
		$this->load = new Loader(
			array(
				'assets' => array(
					'./assets',
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name : '',
					'./overrides/modules/'.$module_name,
					'./neofrag/modules/'.$module_name,
					'./modules/'.$module_name
				),
				'controllers' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/controllers' : '',
					'./overrides/modules/'.$module_name.'/controllers',
					'./neofrag/modules/'.$module_name.'/controllers',
					'./modules/'.$module_name.'/controllers'
				),
				'forms' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/forms' : '',
					'./overrides/modules/'.$module_name.'/forms',
					'./neofrag/modules/'.$module_name.'/forms',
					'./modules/'.$module_name.'/forms'
				),
				'helpers' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/helpers' : '',
					'./overrides/modules/'.$module_name.'/helpers',
					'./neofrag/modules/'.$module_name.'/helpers',
					'./modules/'.$module_name.'/helpers'
				),
				'lang' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/lang' : '',
					'./overrides/modules/'.$module_name.'/lang',
					'./neofrag/modules/'.$module_name.'/lang',
					'./modules/'.$module_name.'/lang'
				),
				'libraries' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/libraries' : '',
					'./overrides/modules/'.$module_name.'/libraries',
					'./neofrag/modules/'.$module_name.'/libraries',
					'./modules/'.$module_name.'/libraries'
				),
				'models' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/models' : '',
					'./overrides/modules/'.$module_name.'/models',
					'./neofrag/modules/'.$module_name.'/models',
					'./modules/'.$module_name.'/models'
				),
				'views' => array(
					!empty($theme_name) ? './themes/'.$theme_name.'/overrides/modules/'.$module_name.'/views' : '',
					'./overrides/modules/'.$module_name.'/views',
					'./neofrag/modules/'.$module_name.'/views',
					'./modules/'.$module_name.'/views'
				)
			),
			NeoFrag::loader()
		);

		$this->name = $module_name;

		$this->set_path();
	}

	public function run($args = array())
	{
		if (!$this->access($this->name, 'module_access'))
		{
			$this->unset_module();

			if ($this->user())
			{
				$this->load->module('error', 'unauthorized');
			}
			else
			{
				$this->load->module('user', 'login', NeoFrag::UNCONNECTED);
			}

			return;
		}
		
		//Vérification des droits d'accés aux pages d'administration
		if ($this->config->admin_url)
		{
			if ($this->user())
			{
				if (!$this->user('admin'))
				{
					$this->config->admin_url = FALSE;
					$this->unset_module();
					$this->load->module('error', 'unauthorized');
					return;
				}
			}
			else
			{
				$this->config->admin_url = FALSE;
				$this->unset_module();
				$this->load->module('user', 'login', NeoFrag::UNCONNECTED);
				return;
			}
		}

		//Méthode par défault
		if (empty($args))
		{
			$method = 'index';
		}
		//Méthode définie par routage
		else if (!empty($this->routes))
		{
			$method = $this->get_method($args);
		}
		
		//Routage automatique
		if (!isset($method))
		{
			if ($args[0])
			{
				$method = str_replace('-', '_', $args[0]);
				$args   = array_offset_left($args);
			}
			else
			{
				$this->unset_module();
				$this->load->module('error');
				return;
			}
		}

		$ajax = $this->config->ajax_url;
		
		//Checker Controller
		if (!is_null($checker = $this->load->controller(($this->config->admin_url ? 'admin_' : '').($ajax ? 'ajax_' : '').'checker')) && method_exists($checker, $method))
		{
			try
			{
				$args = call_user_func_array(array($checker, $method), $args);

				if (!is_array($args) && !is_null($args))
				{
					$this->append_output($args);
					return;
				}
			}
			catch (Exception $error)
			{
				$this->_checker($error->getMessage());
				return;
			}
		}

		if ($this->_module_name == 'error')
		{
			$controller_name = 'index';
		}
		else if ($this->config->admin_url)
		{
			$controller_name = $ajax ? 'admin_ajax' : 'admin';
		}
		else if ($ajax)
		{
			$controller_name = 'ajax';
		}
		else
		{
			$controller_name = 'index';
		}
		
		//Controller
		if (!is_null($controller = $this->load->controller($controller_name)))
		{
			try
			{
				$this->add_data('module_title', $this->get_title());
				$this->add_data('module_method', $method);
				
				if (($output = $controller->method($method, $args)) !== FALSE)
				{
					$this->segments = array($this->name, $method);
					$this->append_output($output);
					return;
				}
				
				throw new Exception(NeoFrag::UNFOUND);
			}
			catch (Exception $error)
			{
				$this->_checker($error->getMessage());
				return;
			}
		}

		$this->unset_module();
		$this->load->module('error');
	}

	private function _checker($error)
	{
		//Gestion des codes d'erreurs remontés par les Exceptions
		if (is_numeric($error))
		{
			$this->unset_module();
			
			if ((int)$error === NeoFrag::UNFOUND)
			{
				$this->load->module('error');
			}
			else if ((int)$error === NeoFrag::UNAUTHORIZED)
			{
				if ($this->user())
				{
					$this->load->module('error', 'unauthorized');
				}
				else
				{
					$this->load->module('user', 'login', NeoFrag::UNAUTHORIZED);
				}
			}
			else if ((int)$error === NeoFrag::UNCONNECTED)
			{
				$this->load->module('user', 'login', NeoFrag::UNCONNECTED);
			}
		}
		//Gestion des redirections demandées par les Exceptions
		else
		{
			call_user_func_array(array($this->load, 'module'), explode('/', $error));
		}
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
		if (!is_string($this->_output))
		{
			$this->_output = display($this->_output);
		}
		
		return $this->_output;
	}

	public function add_action($url, $title, $icon = '')
	{
		$this->_actions[] = array($url, $title, $icon);
	}

	public function get_actions()
	{
		return $this->_actions;
	}

	public function get_title()
	{
		static $title;
		
		if (is_null($title))
		{
			$title = $this->load->lang($this->title, NULL);
		}
		
		return $title;
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
			if (preg_match('#^'.str_replace(array_map(function($a){ return '{'.$a.'}'; }, array_keys(self::$patterns)) + array('#'), array_values(self::$patterns) + array('\#'), $route).'$#', $url, $matches))
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
	
	public function get_access($type = NULL)
	{
		if (method_exists($this, 'access'))
		{
			$access = $this::access();
			
			if ($type === NULL)
			{
				return $access;
}
			else if (isset($access[$type]))
			{
				return $access[$type];
			}
		}

		return array();
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/classes/module.php
*/