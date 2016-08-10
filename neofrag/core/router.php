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

class Router extends Core
{
	public $segments = array();

	public function exec()
	{
		$segments = array('error');

		if ((in_array($this->config->extension_url, array('html', 'json', 'xml', 'txt')) || is_asset()) && !in_string('//', $this->config->request_url))
		{
			$segments = $this->config->segments_url;
			
			if ($segments[0] == 'index')
			{
				$segments = array_merge(explode('/', $this->config->nf_default_page), array_offset_left($segments));
			}
			
			if ($this->config->admin_url && $this->config->request_url != 'admin.html')
			{
				$segments = array_offset_left($segments);
			}
			
			if ($this->config->ajax_url)
			{
				$segments = array_offset_left($segments);
			}
			
			if ($this->config->admin_url && !$this->user('admin'))
			{
				$this->config->admin_url = FALSE;
				
				if ($this->user())
				{
					$segments = array('error', 'unauthorized');
				}
				else
				{
					$segments = array('user', 'login', NeoFrag::UNCONNECTED);
				}
			}
		}

		$this->load->theme = $this->load->theme($this->config->admin_url ? 'admin' : ($this->config->nf_default_theme ?: 'default'))->load();

		$this->_load($segments);
		
		return $this;
	}
	
	public function ajax()
	{
		return 	$this->config->ajax_url ||
				($this->config->ajax_header && $this->config->ajax_allowed) ||
				$this->config->extension_allowed;
	}

	private function _load($segments)
	{
		if (!$module = $this->load->module = $this->load->module(!in_string('_', $segments[0]) ? str_replace('-', '_', $segments[0]) : 'error'))
		{
			return $this->_load($segments[0] != 'pages' ? array_merge(array($this->config->admin_url ? 'admin' : 'pages'), $segments) : array('error'));
		}
		
		array_shift($segments);
		
		if (method_exists($module, 'load'))
		{
			$module->load();
		}

		//Méthode par défault
		if (empty($segments))
		{
			$method = 'index';
		}
		else if (strpos($segments[0], '_') === 0)
		{
			return $this->_load(array('error'));
		}
		//Méthode définie par routage
		else if (!empty($module->routes))
		{
			$method = $module->get_method($segments);
		}

		//Routage automatique
		if (!isset($method))
		{
			$method = str_replace('-', '_', array_shift($segments));
		}
		
		$this->segments = array_merge(array($module->name, $method), $segments);
		
		//Checker Controller
		if (($checker = $module->load->controller(($this->config->admin_url ? 'admin_' : '').($this->config->ajax_url ? 'ajax_' : '').'checker')) && method_exists($checker, $method))
		{
			try
			{
				$segments = call_user_func_array(array($checker, $method), $segments);

				if (!is_array($segments) && $segments !== NULL)
				{
					$module->append_output($segments);
					return;
				}
			}
			catch (Exception $error)
			{
				$this->_check($error->getMessage());
				return;
			}
		}
		
		$controller_name = array();
		
		if ($module->name != 'error')
		{
			if (($ajax_error = $this->config->ajax_header && !$this->config->ajax_url && !$this->config->ajax_allowed) && !post('table_id'))
			{
				return $this->_load(array('error'));
			}
			
			if ($this->config->admin_url)
			{
				$controller_name[] = 'admin';
			}
			
			if ($this->config->ajax_url)
			{
				$controller_name[] = 'ajax';
			}
			
			if (!$controller_name)
			{
				$controller_name[] = 'index';
			}
		}
		else
		{
			$controller_name[] = $this->config->ajax_header ? 'ajax' : 'index';
		}
	
		//Controller
		if ($controller = $module->load->controller(implode('_', $controller_name)))
		{
			try
			{
				$module->add_data('module_title', $module->get_title());
				$module->add_data('module_method', $method);
				
				if (	($output = $controller->method($method, $segments)) !== FALSE &&
						(empty($ajax_error) || $this->config->ajax_allowed) &&
						($this->config->extension_url == 'html' || $this->config->extension_allowed))
				{
					$module->segments = array($module->name, $method);
					$module->append_output($output);
					return;
				}
				
				throw new Exception(NeoFrag::UNFOUND);
			}
			catch (Exception $error)
			{
				$this->_check($error->getMessage());
				return;
			}
		}
		
		$this->_load(array('error'));
	}

	private function _check($error)
	{
		//Gestion des codes d'erreurs remontés par les Exceptions
		if (is_numeric($error))
		{
			if ((int)$error === NeoFrag::UNFOUND)
			{
				$this->_load(array('error'));
			}
			else if ((int)$error === NeoFrag::UNAUTHORIZED)
			{
				if ($this->user())
				{
					$this->_load(array('error', 'unauthorized'));
				}
				else
				{
					$this->_load(array('user', 'login', NeoFrag::UNAUTHORIZED));
				}
			}
			else if ((int)$error === NeoFrag::UNCONNECTED)
			{
				$this->_load(array('user', 'login', NeoFrag::UNCONNECTED));
			}
		}
		//Gestion des redirections demandées par les Exceptions
		else
		{
			call_user_func_array(array($this, '_load'), explode('/', $error));
		}
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/core/router.php
*/