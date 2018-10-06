<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class NeoFrag
{
	//TODO
	static public $route_patterns = [
		'id'         => '([0-9]+?)',
		'key_id'     => '([a-z0-9]+?)',
		'url_title'  => '([a-z0-9-]+?)',
		'url_title*' => '([a-z0-9-/]+?)',
		'page'       => '((?:/?page/[0-9]+?)?)',
		'pages'      => '((?:/?(?:all|page/[0-9]+?(?:/(?:10|25|50|100))?))?)'
	];

	public function __path($type, $file = '', &$dir = [], $callback = 'check_file')
	{
		static $paths = [];

		if (is_a($type, 'closure'))
		{
			$paths[get_called_class()] = $type;
			return $this;
		}

		foreach ($paths[get_called_class()]($this, $type, $file) as $path)
		{
			if ($callback($path))
			{
				if ((NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS) && isset($this->debug) && !is_a($path, 'NF\NeoFrag\Libraries\Date'))
				{
					$this->debug(strtoupper($type), get_class($this), is_object($path) ? get_class($path) : $path);
				}

				return $path;
			}

			$dir[] = $path;
		}

		if ($this != ($NeoFrag = NeoFrag()))
		{
			return $NeoFrag->__path($type, $file, $dir, $callback);
		}
	}

	public function ___load($type, $name, $args = [])
	{
		$paths = [];

		$callback = function(&$path) use ($args){
			if (class_exists($path = class_name('NF\\'.str_replace('/', '\\', $path))))
			{
				$path = NeoFrag($path, $args);
				return TRUE;
			}
		};

		if ($object = $this->__path($type, $name, $paths, $callback))
		{
			return $object;
		}

		trigger_error('Unfound '.$type.': '.$name.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
	}

	public function __isset($name)
	{
		return isset(NeoFrag()->$name);
	}

	public function __get($name)
	{
		if (isset(NeoFrag()->$name))
		{
			return NeoFrag()->$name;
		}
		else
		{
			if (!defined('NEOFRAG_CORE') && substr($name, 0, 5) == 'core_')
			{
				$type = 'core';
				$name = substr($name, 5);
				$args = [];
			}
			else
			{
				$type = 'libraries';
				$args = [property_exists($this, '__caller') ? $this->__caller : (is_a($this, 'NF\NeoFrag\Loadables\Addon') ? $this : NeoFrag())];
			}

			$$name = NULL;

			if ((@include 'config/'.$name.'.php') && $$name)
			{
				$args[] = $$name;
			}

			if (!($object = @NeoFrag()->___load($type, $name, $args)))
			{
				$class = explode('_', $name, 2);

				if (array_key_exists(1, $class))
				{
					$class[0] .= 's';
				}

				$object = @NeoFrag()->___load($type, implode('\\', $class), $args);
			}

			if ($type == 'core')
			{
				$this->$name = $object;
			}

			return $object;
		}

		trigger_error('Undefined property: '.get_class($this).'::$'.$name, E_USER_WARNING);
	}

	public function __call($name, $args)
	{
		if ($name == 'clone')
		{
			return clone $this;
		}

		$callback = NULL;

		if (preg_match('/^(?:static_)?(.+?)_if$/', $name, $match))
		{
			if (!$contition = $args[0])
			{
				return method_exists($this, '__extends') ? $this->__extends() : $this;
			}

			$args = array_slice($args, 1);

			if (($name = $match[1]) != 'exec')
			{
				array_walk($args, function(&$a) use ($contition){
					if (is_a($a, 'closure'))
					{
						$a = $a($contition, $this);
					}
				});
			}

			$callback = [$this, $name];
		}

		if (preg_match('/^static_(.+)/', $name, $match))
		{
			return forward_static_call_array($callback ?: [$this, $match[1]], $args);
		}
		else if (is_a($class = 'NF\NeoFrag\Displayables\\'.$name, 'NF\NeoFrag\Displayable', TRUE))
		{
			$callback = NeoFrag($class);
		}
		else if (is_a($class = 'NF\NeoFrag\Addons\\'.$name, 'NF\NeoFrag\Loadable', TRUE))
		{
			return forward_static_call_array($class.'::__load', [NeoFrag(), $args]);
		}
		else if (is_a($class = 'NF\NeoFrag\Loadables\\'.$name, 'NF\NeoFrag\Loadable', TRUE))
		{
			return forward_static_call_array($class.'::__load', [property_exists($this, '__caller') ? $this->__caller : (is_a($this, 'NF\NeoFrag\Loadables\Addon') ? $this : NeoFrag()), $args]);
		}
		else if (!$callback && is_callable($library = $this->$name))
		{
			$callback = $library;
		}

		if ($callback)
		{
			return call_user_func_array($callback, $args);
		}

		trigger_error('Call to undefined method '.get_class($this).'::'.$name.'()', E_USER_WARNING);
	}

	public function exec($callback)
	{
		$callback($this);
		return $this;
	}

	public function __debugInfo()
	{
		$properties = [];

		foreach (get_object_vars($this) as $key => $value)
		{
			$properties[$key] = $key == '__caller' ? get_class($value) : $value;
		}

		return $properties;
	}
}
