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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Loader extends Core
{
	public $paths       = array();
	public $libraries   = array();
	public $helpers     = array();
	public $widgets     = array();
	public $controllers = array();
	public $models      = array();
	public $data        = array();
	public $css         = array();
	public $js          = array();
	public $js_load     = array();
	public $module;
	public $theme;
	public $parent;
	public $object;

	public function __construct($paths = array(), $parent = NULL)
	{
		$this->load =& $this;

		$this->parent = $parent;

		if (is_null($this->parent))
		{
			$this->paths = $paths;

			//Pour chaque helpers du dossier helpers :
			//TOTO remplacer par scandir()
			$helpers = opendir('./neofrag/helpers');
			while (($helper_name = readdir($helpers)) !== FALSE)
			{
				if (!in_array($helper_name, array('.', '..')) && preg_match('/\.php$/', $helper_name))
				{
					$this->helper(substr($helper_name, 0, -4));
				}
			}
		}
		else
		{
			$this->paths = array_merge_recursive($paths, $this->parent->paths);
		}

		$this->paths = array_map('array_filter', $this->paths);

		$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
		if (isset($backtrace[1]['object']) && get_class($object = $backtrace[1]['object']) != get_class($this))
		{
			$this->object = $object;
		}
	}

	public function get_libraries()
	{
		if (!is_null($this->parent))
		{
			return array_merge($this->parent->get_libraries(), $this->libraries);
		}
		else
		{
			return $this->libraries;
		}
	}

	public function get_library($name)
	{
		if (isset($this->libraries[$name]))
		{
			return $this->libraries[$name];
		}
		else if (!is_null($this->parent))
		{
			return $this->parent->get_library($name);
		}
		else
		{
			return NULL;
		}
	}

	public function core()
	{
		return call_user_func_array(array($this, '_load'), array(2, 'core', func_get_args()));
	}
	
	public function library($library_name, $trace = 2)
	{
		return call_user_func_array(array($this, '_load'), array($trace, 'libraries', array($library_name)));
	}
	
	private function _load($trace, $type, $libraries)
	{
		$count = count($libraries);
		
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $trace + 1)[$trace];
		$id = $backtrace['file'].$backtrace['line'];
		
		foreach ($libraries as $library_name)
		{
			if (in_array($library_name, array_map('cc2u', array_map('get_class', $this->get_libraries()))))
			{
				$this->profiler->log('Librairie '.$library_name.' déjà chargée', Profiler::INFO);
				
				if ($count == 1)
				{
					return $this->get_library(array_search($library_name, array_map('cc2u', array_map('get_class', $this->get_libraries()))));
				}
				
				continue;
			}

			foreach ($this->paths[$type] as $path)
			{
				if (!file_exists($library_path = $path.'/'.$library_name.'.php'))
				{
					continue;
				}
				
				//Appel de la librairie
				require_once $library_path;

				//S'il existe un fichier de configuration pour cette librairie
				foreach ($this->paths['config'] as $path)
				{
					if (file_exists($config_path = $path.'/'.$library_name.'.php'))
					{
						//Appeler la configuration
						include $config_path;
					}
				}

				//Création de l'instance de la librairie
				//S'il existe une variable de configuration (du nom de la librairie), on la transmet au constructeur
				$library_class = u2ucc($library_name);
				$library_new   = isset($$library_name) ? new $library_class($$library_name) : new $library_class;
				
				$library_new->set_id($id.$library_name);

				if ($count == 1)
				{
					return $library_new;
				}
			}
		}
	}

	public function helper($helper_name)
	{
		foreach ($this->paths['helpers'] as $path)
		{
			if (!file_exists($helper_path = $path.'/'.$helper_name.'.php'))
			{
				continue;
			}

			$this->helpers[] = array($helper_path, $helper_name);
			include_once $helper_path;
			
			break;
		}
		
		return $this;
	}

	public function init_module()
	{
		return call_user_func_array(array($this, 'module'), func_get_args());
	}
	
	public function module($name)
	{
		$args = array();
		foreach (array_offset_left(func_get_args()) as $arg)
		{
			if (is_array($arg))
			{
				$args = array_merge($args, $arg);
			}
			else
			{
				$args[] = $arg;
			}
		}

		if (func_num_args() == 1 && in_string('/', $name))
		{
			$args = explode('/', $name);

			$name = $args[0];
			$args = array_offset_left($args);
		}
		
		$init = ($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)) && isset($backtrace[2]['function']) && $backtrace[2]['function'] == 'init_module';
		
		$module_name = str_replace('-', '_', $name);
		
		if (in_array($module_name, array_keys(array_filter($this->addons('module')))))
		{
			reset($this->paths['modules']);
			
			while (list(, $path) = each($this->paths['modules']))
			{
				if (!file_exists($module_path = $path.'/'.$module_name.'/'.$module_name.'.php'))
				{
					continue;
				}

				if (!$init)
				{
					$this->profiler->log('Module '.$module_name.' chargé : '.$path, Profiler::INFO);
				}
				
				$module_class = 'm_'.$module_name;

				if (in_string('/overrides/', $module_path))
				{
					while (list(, $path) = each($this->paths['modules']))
					{
						if (!file_exists($o_module_path = $path.'/'.$module_name.'/'.$module_name.'.php'))
						{
							continue;
						}
						
						include_once $o_module_path;
						break;
					}
					
					$module_class = 'o_'.$module_class;
				}

				include_once $module_path;

				$module = new $module_class($module_name);

				if (method_exists($module, 'load') && !$init)
				{
					$module->load();
				}

				if (is_null(NeoFrag::loader()->module) && !$init)
				{
					NeoFrag::loader()->module =& $module;
					$module->run($args);
				}

				return $module;
			}
		}
		
		if ($init)
		{
			return NULL;
		}

		if ($this->config->admin_url)
		{
			return call_user_func_array(array($this, 'module'), array_merge(array('admin', $module_name), $args));
		}

		if (!$this->config->admin_url && !$this->config->ajax_url)
		{
			return $this->module('pages', $name);
		}

		return $this->module('error');
	}

	public function theme($theme_name, $load_theme = TRUE)
	{
		reset($this->paths['themes']);
		
		while (list(, $path) = each($this->paths['themes']))
		{
			if (!file_exists($theme_path = $path.'/'.$theme_name.'/'.strtolower($theme_name).'.php'))
			{
				continue;
			}

			$this->profiler->log('Thème '.$theme_name.' chargé : '.$path, Profiler::INFO);
			
			$theme_class = 't_'.strtolower($theme_name);

			if (in_string('/overrides/', $theme_path))
			{
				while (list(, $path) = each($this->paths['themes']))
				{
					if (!file_exists($o_theme_path = $path.'/'.$theme_name.'/'.strtolower($theme_name).'.php'))
					{
						continue;
					}
					
					include_once $o_theme_path;
					break;
				}

				$theme_class = 'o_'.$theme_class;
			}
			
			include_once $theme_path;

			$theme = new $theme_class($theme_name);

			if ($load_theme)
			{
				$this->theme =& $theme;

				if ($theme_name != 'default')
				{
					array_unshift($this->paths['assets'],  './themes/'.$theme_name);
					array_unshift($this->paths['views'],   './themes/'.$theme_name.'/overrides/views',   './themes/'.$theme_name.'/views');
				}
			}

			return $theme;
		}

		if (!$load_theme)
		{
			return NULL;
		}

		return $this->theme('default');
	}

	public function widget($widget_name)
	{
		foreach ($this->paths['widgets'] as $path)
		{
			if (!file_exists($widget_path = $path.'/'.$widget_name.'/'.strtolower($widget_name).'.php'))
			{
				continue;
			}

			$this->profiler->log('Widget '.$widget_name.' chargé : '.$path, Profiler::INFO);

			include_once $widget_path;

			$widget_class = 'w_'.strtolower($widget_name);

			return new $widget_class($widget_name);
		}

		return $this->widget('error');
	}

	public function controller($controller)
	{
		if (in_array($controller, array_keys($this->controllers)))
		{
			$this->profiler->log('Contrôleur '.$controller.' déjà chargé', Profiler::INFO);
			return $this->controllers[$controller];
		}

		reset($this->paths['controllers']);
		
		while (list(, $path) = each($this->paths['controllers']))
		{
			if (!file_exists($controller_path = $path.'/'.$controller.'.php'))
			{
				continue;
			}

			$this->profiler->log('Contrôleur '.$controller.' chargé : '.$path, Profiler::INFO);

			$controller_name = get_class($this->object).'_c_'.$controller;

			if (in_string('/overrides/', $controller_path))
			{
				while (list(, $path) = each($this->paths['controllers']))
				{
					if (!file_exists($o_controller_path = $path.'/'.$controller.'.php'))
					{
						continue;
					}
					
					include_once $o_controller_path;
					break;
				}

				$controller_name = 'o_'.$controller_name;
			}

			include_once $controller_path;

			if (!class_exists($controller_name))
			{
				$controller_name = preg_replace('/^((o_)?)w_(.+?)$/', '\1m_\3', $controller_name);
			}

			if (class_exists($controller_name))
			{
				$controller_instance = new $controller_name($this->object);

				$this->controllers[$controller] =& $controller_instance;

				return $controller_instance;
			}
			else
			{
				break;
			}
		}

		$this->profiler->log('Contrôleur '.$controller.' inexistant : '.$path, Profiler::ERROR);
		return NULL;
	}

	public function model($module, $model)
	{
		if (in_array($model, array_keys($this->models)))
		{
			$this->profiler->log('Modèle '.$model.' déjà chargé', Profiler::INFO);
			return $this->models[$model];
		}

		foreach ($this->paths['models'] as $path)
		{
			if (!file_exists($model_path = $path.'/'.$model.'.php'))
			{
				continue;
			}

			$this->profiler->log('Modèle '.$model.' chargé : '.$path, Profiler::INFO);

			include_once $model_path;
			$model_name = get_class($module).'_m_'.$model;

			if (!class_exists($model_name))
			{
				$model_name = preg_replace('/^((o_)?)w_(.+?)$/', '\1m_\3', $model_name);
			}

			if (class_exists($model_name))
			{
				$model_instance = new $model_name;

				$this->models[$model] =& $model_instance;

				return $model_instance;
			}
			else
			{
				break;
			}
		}

		$this->profiler->log('Modèle '.$model.' inexistant : '.$path, Profiler::ERROR);
		return NULL;
	}

	public function form($form, $values)
	{
		foreach ($this->paths['forms'] as $path)
		{
			if (!file_exists($form_path = $path.'/'.$form.'.php'))
			{
				continue;
			}

			$this->profiler->log('Formulaire '.$form.' chargé : '.$path, Profiler::INFO);

			foreach ($values as $var => $value)
			{
				$$var = $value;
			}
			
			include $form_path;

			if (!empty($rules))
			{
				return $rules;
			}
			else
			{
				break;
			}
		}

		$this->profiler->log('Formulaire '.$form.' inexistant : '.$path, Profiler::ERROR);
		return NULL;
	}
	
	public function view($template, $data = array())
	{
		foreach ($this->paths['views'] as $path)
		{
			if (!file_exists($template_path = $path.'/'.$template.'.tpl.php'))
			{
				continue;
			}

			return $this->template->load($template_path, array_merge($data, $this->data), $this);
		}

		return '';
	}
	
	public function lang($name)
	{
		$args = func_get_args();
		$name = array_shift($args);

		if (count($args) == 1 && $args[0] === NULL)
		{
			$loader = $this;
			return preg_replace_callback('/\{lang (.+?)\}/', function($a) use ($loader){
				return $loader->lang($a[1]);
			}, $name);
		}
		
		static $langs;
		
		foreach ($this->paths['lang'] as $path)
		{
			if (!file_exists($lang_path = $path.'/'.$this->config->lang.'.php'))
			{
				continue;
			}
			
			if (isset($langs[$lang_path]))
			{
				$lang = $langs[$lang_path];
			}
			else
			{
				$lang = array();
				include $lang_path;
				$langs[$lang_path] = $lang;
			}

			if (isset($lang[$name]))
			{
				if (is_array($lang[$name]))
				{
					return $lang[$name];
				}
				else
				{
					array_unshift($args, $lang[$name]);
					if (($translation = call_user_func_array('p11n', $args)) !== FALSE)
					{
						return $translation;
					}
				}
				
				break;
			}
		}
		
		//TODO chercher la traduc en english
		
		$this->profiler->log(NeoFrag::loader()->lang('unfound_translation', isset($translation), $name), Profiler::WARNING);
		return '___'.$name;
	}

	public function profiler($type)
	{
		if ($type == 'core')
		{
			$name = 'NeoFrag Core';
		}
		else if ($type == 'module')
		{
			$name = $this->template->parse(NeoFrag::loader()->module->get_title(), array(), NeoFrag::loader()->module);
		}

		$output = '	<div style="clear: both;"></div>
					<h4>'.$name.'</h4>
					<div class="well" style="overflow: hidden; padding-bottom: 0;">';
		$key = 0;
		foreach ($this->libraries as $library)
		{
			$output .= '<div class="alert alert-info" style="float: left; text-align: center; margin: 0 20px 20px 0;"><b>'.$key++.'. '.get_class($library).'</b><br />'.$library->path.'</div>';
		}

		$output .= '	<div style="clear: both;"></div>';

		foreach ($this->helpers as $key => $helper)
		{
			list($path, $name) = $helper;
			$output .= '<div class="alert alert-success" style="float: left; text-align: center; margin: 0 20px 20px 0;"><b>'.$key.'. '.u2ucc($name).'</b><br />'.$path.'</div>';
		}

		$output .= '	<div style="clear: both;"></div>';

		/*foreach ($this->modules as $module)
		{
			$output .= $module->load->profiler('module');
		}*/

		$output .= '</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/core/loader.php
*/