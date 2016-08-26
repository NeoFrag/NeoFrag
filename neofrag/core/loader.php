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

class Loader extends Core
{
	public $libraries   = [];
	public $helpers     = [];
	public $controllers = [];
	public $models      = [];
	public $views       = [];
	public $forms       = [];
	public $langs       = [];
	public $data        = [];
	public $object;

	public function __construct($paths, $object = NULL)
	{
		call_user_func($this->update = function() use ($paths, $object){
			if (is_callable($paths))
			{
				$this->paths = call_user_func($paths);
			}
			else
			{
				$this->paths = $paths;
				unset($this->update);
			}

			$this->object = $object;

			if ($object !== NULL)
			{
				$this->paths = array_merge_recursive($this->paths, NeoFrag::loader()->paths);
			}

			$this->paths = array_map('array_filter', $this->paths);
		});
	}

	public function module($name, $force = FALSE)
	{
		return $this->_load($name, 'module', 'm_'.$name, NeoFrag::loader()->modules, $name.'/'.$name, $force, function() use ($name){
			return $this->addons->is_enabled($name, 'module') && $this->access($name, 'module_access');
		});
	}

	public function theme($name, $force = FALSE)
	{
		return $this->_load($name, 'theme', 't_'.$name, NeoFrag::loader()->themes, $name.'/'.$name, $force);
	}

	public function widget($name, $force = FALSE)
	{
		return $this->_load($name, 'widget', 'w_'.$name, NeoFrag::loader()->widgets, $name.'/'.$name, $force, function() use ($name){
			return $this->addons->is_enabled($name, 'widget');
		});
	}

	private function _load($name, $type, $class, &$objects, $filename = NULL, $force = FALSE, $is_enabled = NULL)
	{
		if (($force && !empty($objects[$name])) || (!$force && array_key_exists($name, $objects)))
		{
			return $objects[$name];
		}
		
		if (!$force && is_callable($is_enabled) && !$is_enabled())
		{
			return $objects[$name] = NULL;
		}

		if ($filename === NULL)
		{
			$filename = $name;
		}
		
		if (is_callable($this->update))
		{
			call_user_func($this->update);
		}

		reset($this->paths[$type.'s']);
		
		$object = NULL;

		while (list(, $dir) = each($this->paths[$type.'s']))
		{
			if (!check_file($path = $dir.'/'.$filename.'.php', $force))
			{
				continue;
			}

			if ($type == 'model' && in_string('modules/', $dir))
			{
				$class = preg_replace('/^w_/', 'm_', $class);
			}

			if (in_string('overrides/', $path))
			{
				while (list(, $dir) = each($this->paths[$type.'s']))
				{
					if (!check_file($o_path = $dir.'/'.$filename.'.php'))
					{
						continue;
					}

					include_once $o_path;

					break;
				}

				$class = 'o_'.$class;
			}

			include_once $path;

			$object = load($class, $name, $type);
			
			break;
		}

		if (!$force)
		{
			$objects[$name] = $object;
		}

		return $object;
	}

	public function helper($name)
	{
		foreach ($this->paths['helpers'] as $dir)
		{
			if (!check_file($path = $dir.'/'.$name.'.php'))
			{
				continue;
			}

			$this->helpers[] = [$path, $name];

			include_once $path;

			break;
		}

		return $this;
	}

	public function controller($name)
	{
		if ($controller = $this->_load($name, 'controller', preg_replace('/^o_/', '', get_class($this->object)).'_c_'.$name, $this->controllers))
		{
			$controller->load = $this;
		}

		return $controller;
	}

	public function model($name = NULL)
	{
		if ($name === NULL)
		{
			$name = $this->object->name;
		}

		if ($model = $this->_load($name, 'model', preg_replace('/^o_/', '', get_class($this->object)).'_m_'.$name, $this->models))
		{
			$model->load = $this;
		}

		return $model;
	}

	public function view($name, $data = [])
	{
		foreach ($this->paths['views'] as $dir)
		{
			if (check_file($path = $dir.'/'.$name.'.tpl.php'))
			{
				$data = array_merge($data, $this->data);

				if ($this->debug->is_enabled())
				{
					$this->views[] = [$path, $name.'.tpl.php', $data];
				}

				return $this->template->load($path, $data, $this);
			}
		}
	}

	public function form($form, $values)
	{
		foreach ($this->paths['forms'] as $dir)
		{
			if (!check_file($path = $dir.'/'.$form.'.php'))
			{
				continue;
			}
			
			if ($this->debug->is_enabled())
			{
				$this->forms[$dir] = [$path, $form.'.php', $values];
			}

			foreach ($values as $var => $value)
			{
				$$var = $value;
			}

			include $path;

			if (!empty($rules))
			{
				return $rules;
			}
			else
			{
				break;
			}
		}
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

		foreach ($this->paths['lang'] as $dir)
		{
			foreach ($this->config->langs as $language)
			{
				if (!check_file($path = $dir.'/'.$language.'.php'))
				{
					continue;
				}

				if (isset($this->langs[$path]))
				{
					$lang = $this->langs[$path];
				}
				else if (isset(NeoFrag::loader()->langs[$path]))
				{
					$lang = NeoFrag::loader()->langs[$path];
				}
				else
				{
					$lang = [];

					include $path;

					$this->langs[$path] = $lang;
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
							/*if ($this->debug->is_enabled())
							{
								$translation = '❤ '.$translation.' ❤';
							}*/
							
							return $translation;
						}
					}

					break 2;
				}
			}
		}

		return $name;
	}

	public function debugbar($title = 'Loader')
	{
		$output = '<span class="label label-info">'.$title.(!empty($this->override) ? ' '.icon('fa-code-fork') : '').'</span>';
		
		$this->debug->timeline($output, $this->time[0], $this->time[1]);

		$output = '	<ul>
						<li>
							'.$output;

		foreach ([
			[$this->modules,     'Modules',     'default', function($a){ return $a->debug('default'); }],
			[$this->themes,      'Themes',      'primary', function($a){ return $a->debug('primary'); }],
			[$this->widgets,     'Widgets',     'success', function($a){ return $a->debug('success'); }],
			[$this->libraries,   'Libraries',   'info',    function($a){ return $a->debug('info'); }],
			[$this->helpers,     'Helpers',     'warning', function($a){ return '<span class="label label-warning">'.$a[1].'</span>'; }],
			[$this->controllers, 'Controllers', 'danger',  function($a){ return $a->debug('danger'); }],
			[$this->models,      'Models',      'default', function($a){ return $a->debug('default'); }],
			[$this->views,       'Views',       'primary', function($a){ return '<span class="label label-primary">'.$a[1].'</span>'; }],
			[$this->forms,       'Forms',       'success', function($a){ return '<span class="label label-success">'.$a[1].'</span>'; }],
			[$this->langs,       'Locales',     'info',    function($a, $b){ return '<span class="label label-info">'.$b.'</span>'; }]
		] as $vars)
		{
			list($objects, $name, $class, $callback) = $vars;

			if (!empty($objects))
			{
				$output .= '	<ul>
									<li>
										<span class="label label-'.$class.'">'.$name.'</span>
										<ul>';

				foreach (array_filter($objects) as $key => $object)
				{
					$output .= '			<li>'.$callback($object, $key).'</li>';
				}

				$output .= '			</ul>
									</li>
								</ul>';
			}
		}

		return $output.'</li>
					</ul>';
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/core/loader.php
*/