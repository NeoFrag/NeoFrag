<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class NeoFrag
{
	const UNFOUND      = 0;
	const UNAUTHORIZED = 1;
	const UNCONNECTED  = 2;

	const LIVE_EDITOR  = 1;
	const ZONES        = 2;
	const ROWS         = 4;
	const COLS         = 8;
	const WIDGETS      = 16;

	static public $route_patterns = [
		'id'         => '([0-9]+?)',
		'key_id'     => '([a-z0-9]+?)',
		'url_title'  => '([a-z0-9-]+?)',
		'url_title*' => '([a-z0-9-/]+?)',
		'page'       => '((?:/?page/[0-9]+?)?)',
		'pages'      => '((?:/?(?:all|page/[0-9]+?(?:/(?:10|25|50|100))?))?)'
	];

	static public function live_editor()
	{
		if (($live_editor = post('live_editor')) && NeoFrag()->user('admin'))
		{
			NeoFrag()->session->set('live_editor', $live_editor);
			return $live_editor;
		}
		
		return FALSE;
	}

	public function __isset($name)
	{
		return isset($this->load->libraries[$name]) || isset(NeoFrag()->libraries[$name]);
	}

	public function __get($name)
	{
		if (property_exists($this, 'load') && isset($this->load->libraries[$name]))
		{
			return $this->load->libraries[$name];
		}
		else if (isset(NeoFrag()->libraries[$name]))
		{
			return NeoFrag()->libraries[$name];
		}
		else
		{
			$type = 'libraries';
			
			if (preg_match('/^core_(.+)/', $name, $match))
			{
				$name = $match[1];
				$type = 'core';
			}
			
			foreach ($this->load->paths($type) as $dir)
			{
				if (!check_file($path = $dir.'/'.$name.'.php') && (!preg_match('/^(.+?)_(.+)/', $name, $match) || !check_file($path = $dir.'/'.$match[1].'s/'.$match[2].'.php')))
				{
					continue;
				}

				require_once $path;

				foreach ($this->load->paths('config') as $dir)
				{
					if (check_file($path = $dir.'/'.$name.'.php'))
					{
						include $path;
					}
				}

				if (isset($$name))
				{
					$library = load($name, $$name);
				}
				else
				{
					$library = load($name);
				}

				if (!isset($library->load))
				{
					$library->load = $this->load;
				}

				return $this->load->libraries[$library->name = $name] = $library->set_id();
			}
		}

		trigger_error('Undefined property: '.get_class($this).'::$'.$name, E_USER_WARNING);
	}

	public function __call($name, $args)
	{
		$callback = NULL;

		if (preg_match('/^(?:static_)?(.+?)_if$/', $name, $match))
		{
			if (!$args[0])
			{
				return $this;
			}

			$args = array_slice($args, 1);

			array_walk($args, function(&$a){
				if (is_a($a, 'closure'))
				{
					$a = $a();
				}
			});

			$callback = [$this, $match[1]];
		}

		if (preg_match('/^static_(.+)/', $name, $match))
		{
			return forward_static_call_array($callback ?: [$this, $match[1]], $args);
		}

		if (!$callback && is_callable($library = $this->$name ?: NeoFrag()->$name))
		{
			$callback = $library;
		}

		if ($callback)
		{
			return call_user_func_array($callback, $args);
		}

		trigger_error('Call to undefined method '.get_class($this).'::'.$name.'()', E_USER_WARNING);
	}

	public function ajax()
	{
		$this->url->ajax_allowed = TRUE;
		return $this;
	}

	public function extension($extension)
	{
		if (in_array($extension, ['json', 'xml', 'txt']))
		{
			if ($this->url->extension != $extension)
			{
				throw new Exception(NeoFrag::UNFOUND);
			}

			$this->url->extension     = $extension;
			$this->url->extension_allowed = TRUE;

			$this->ajax();
		}

		return $this;
	}

	public function add_data($data, $content)
	{
		$this->load->data[$data] = $content;
		return $this;
	}

	public function css($file, $media = 'screen')
	{
		NeoFrag()->css[] = [$file, $media, $this->load];
		return $this;
	}

	public function js($file)
	{
		NeoFrag()->js[] = [$file, $this->load];
		return $this;
	}

	public function js_load($function)
	{
		NeoFrag()->js_load[] = $function;
		return $this;
	}

	public function module($name, $force = FALSE)
	{
		return $this->_load($name, 'module', 'm_'.$name, NeoFrag()->modules, $name.'/'.$name, $force, function() use ($name){
			return $this->addons->is_enabled($name, 'module') && $this->access($name, 'module_access');
		});
	}

	public function theme($name, $force = FALSE)
	{
		return $this->_load($name, 'theme', 't_'.$name, NeoFrag()->themes, $name.'/'.$name, $force);
	}

	public function widget($name, $force = FALSE)
	{
		return $this->_load($name, 'widget', 'w_'.$name, NeoFrag()->widgets, $name.'/'.$name, $force, function() use ($name){
			return $this->addons->is_enabled($name, 'widget');
		});
	}

	public function controller($name)
	{
		if ($controller = $this->_load($name, 'controller', preg_replace('/^o_/', '', get_class($this->load->caller)).'_c_'.$name, $this->load->controllers))
		{
			$controller->load = $this->load;

			if (is_a($this->load->caller, $type = 'module') || is_a($this->load->caller, $type = 'widget'))
			{
				$controller->$type = $this->load->caller;
			}
		}

		return $controller;
	}

	public function model($name = NULL)
	{
		if ($name === NULL)
		{
			$name = $this->load->caller->name;
		}

		if ($model = $this->_load($name, 'model', preg_replace('/^o_/', '', get_class($this->load->caller)).'_m_'.$name, $this->load->models))
		{
			$model->load = $this->load;
		}

		return $model;
	}

	public function authenticator($name, $enabled, $settings = [])
	{
		return $this->_load($name, 'authenticator', 'a_'.$name, NeoFrag()->authenticators, NULL, FALSE, NULL, [$name, $enabled, $settings]);
	}

	public function helper($name)
	{
		foreach ($this->load->paths('helpers') as $dir)
		{
			if (!check_file($path = $dir.'/'.$name.'.php'))
			{
				continue;
			}

			$this->load->helpers[] = [$path, $name];

			include_once $path;

			break;
		}

		return $this;
	}

	public function form($form)
	{
		foreach ($paths = $this->load->paths('forms') as $dir)
		{
			if (!check_file($path = $dir.'/'.$form.'.php'))
			{
				continue;
			}
			
			if ($this->debug->is_enabled())
			{
				$this->load->forms[$dir] = [$path, $form.'.php'];
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

		trigger_error('Unfound form: '.$form.' in paths ['.implode(', ', $paths).']', E_USER_WARNING);
	}

	public function lang($name)
	{
		$args = func_get_args();
		$name = array_shift($args);

		if (count($args) == 1 && $args[0] === NULL)
		{
			return preg_replace_callback('/\{lang (.+?)\}/', function($a){
				return $this->lang($a[1]);
			}, $name);
		}

		foreach ($paths = $this->load->paths('lang') as $dir)
		{
			foreach ($this->config->langs as $language)
			{
				if (!check_file($path = $dir.'/'.$language.'.php'))
				{
					continue;
				}

				if (isset($this->load->langs[$path]))
				{
					$lang = $this->load->langs[$path];
				}
				else if (isset(NeoFrag()->load->langs[$path]))
				{
					$lang = NeoFrag()->load->langs[$path];
				}
				else
				{
					$lang = [];

					include $path;

					$this->load->langs[$path] = $lang;
				}

				if (isset($lang[$name]))
				{
					if (is_array($lang[$name]))
					{
						return $lang[$name];
					}
					else
					{
						$locale = $lang[$name];

						if (!$args)
						{
							$translation = $locale;
						}
						else
						{
							if (in_string('|', $locale))
							{
								$n      = NULL;
								$locale = explode('|', $locale);
								$count  = count($locale);

								foreach ($locale as $i => &$l)
								{
									if (preg_match('/^\{(\d+?)\}|\[(\d+?),(\d+?|Inf)\]/', $l, $match))
									{
										$n = end($match);

										if ($n == 'Inf')
										{
											break;
										}
									}
									else if ($n === NULL)
									{
										$l = '[0,1]'.$l;
										$n = 1;
									}
									else if ($i == $count - 1)
									{
										$l = '['.++$n.',Inf]'.$l;
									}
									else
									{
										$l = '{'.++$n.'}'.$l;
									}

									unset($l);
								}

								foreach ($locale as $l)
								{
									if (preg_match('/^\{(\d+?)\}(.*)/', $l, $match) && $args[0] == $match[1])
									{
										$locale = $match[2];
										unset($args[0]);
										break;
									}
									else if (preg_match('/^\[(\d+?),(\d+?|Inf)\](.*)/', $l, $match) && $args[0] >= $match[1] && ($match[2] == 'Inf' || $args[0] <= $match[2]))
									{
										$locale = $match[3];
										unset($args[0]);
										break;
									}
								}
							}

							array_unshift($args, $locale);
							$translation = call_user_func_array('sprintf', $args);
						}

						/*if ($this->debug->is_enabled())
						{
							$translation = '❤ '.$translation.' ❤';
						}*/

						return $translation;
					}
				}
			}
		}

		trigger_error('Unfound lang: '.$name.' in paths ['.implode(', ', $paths).']', E_USER_WARNING);

		return $name;
	}

	public function debug($color, $title = NULL)
	{
		$output = NeoFrag()	->label($title ?: (isset($this->name) ? $this->name : get_class($this)))
							->icon_if(property_exists($this, 'override') && $this->override, 'fa-code-fork')
							->color($color)
							->tooltip(icon('fa-clock-o').' '.round(($this->time[1] - $this->time[0]) * 1000, 2).' ms&nbsp;&nbsp;&nbsp;'.icon('fa-cogs').' '.ceil(($this->memory[1] - $this->memory[0]) / 1024).' kB');

		NeoFrag()->debug->timeline($output, $this->time[0], $this->time[1]);

		return $output;
	}

	private function _load($name, $type, $class, &$objects, $filename = NULL, $force = FALSE, $is_enabled = NULL, $constructor = [])
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

		$paths = $this->load->paths($type.'s');
		
		$object = NULL;

		while (list(, $dir) = each($paths))
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
				while (list(, $dir) = each($paths))
				{
					if (in_string('overrides/', $o_path = $dir.'/'.$filename.'.php') || !check_file($o_path))
					{
						continue;
					}

					include_once $o_path;

					break;
				}

				$class = 'o_'.$class;
			}

			include_once $path;

			$object = call_user_func_array('load', array_merge([$class], $constructor ?: [$name, $type]));
			
			break;
		}

		if (!$force)
		{
			$objects[$name] = $object;
		}

		return $object;
	}
}
