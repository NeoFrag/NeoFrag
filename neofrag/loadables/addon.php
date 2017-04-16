<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Addon extends NeoFrag implements \NF\NeoFrag\Loadable
{
	static protected $_objects = [];

	abstract protected function __info();
	//abstract public function paths();

	static public function __load($caller, $args = [])
	{
		$name  = array_shift($args);
		$addon = array_shift($args);

		if (!isset(static::$_objects[$class = get_called_class()][$name]))
		{
			$dir = ($type = strtolower(preg_replace('/.+Addons\\\/', '', $class))).'s';

			if (!$addon)
			{
				$addon = NeoFrag()->model2('addon')->get($type, $name, FALSE);
			}

			if (!$addon || !$addon())
			{
				static::$_objects[$class][$name] = NULL;
			}
			else if (static::$_objects[$class][$name] = $addon = $caller->___load('addons', static::__class($name), [$addon]))
			{
				$addon->__path(function($caller, $type, $file) use ($dir){
					$file = [$file];

					if (!in_array($type, ['addons', 'assets']) && $type)
					{
						array_unshift($file, $type);
					}

					$file = $dir.'/'.$caller->info()->name.'/'.implode('/', $file);

					if (!NEOFRAG_SAFE_MODE)
					{
						yield 'overrides/'.$file;

						if ($caller->output && ($theme = $caller->output->theme()))
						{
							yield 'themes/'.$theme->info()->name.'/overrides/'.$file;
						}
					}

					yield $file;
				});
			}
		}

		return static::$_objects[$class][$name];
	}

	static public function __class($name)
	{
		return 'Addons\\'.$name.'\\'.$name;
	}

	protected $__info     = [];
	protected $__settings = [];

	public function __construct($addon)
	{
		$this->__info     = [
			'name' => $addon->name
		];

		$this->__settings = (object)$addon->data;

		$this->__addon    = $addon;
	}

	public function info()
	{
		static $info = [];

		if (!array_key_exists($id = spl_object_hash($this), $info))
		{
			$info[$id] = (object)array_merge($this->__info(), $this->__info);
		}

		return $info[$id];
	}

	public function settings()
	{
		return $this->__settings;
	}

	public function is_enabled()
	{
		return !$this->is_removable() || !empty($this->settings()->enabled);
	}

	public function is_deactivatable()
	{
		return !isset(static::$core) || !array_key_exists($this->__info['name'], static::$core) || static::$core[$this->__info['name']];
	}

	public function is_removable()
	{
		return !isset(static::$core) || empty(static::$core[$this->__info['name']]);
	}

	public function install()
	{
		return $this;
	}

	public function uninstall($remove = TRUE)
	{
		if ($remove)
		{
			$this->__addon->delete();
			dir_remove($this->__addon->type->name.'s/'.$this->__info['name']);
		}

		return $this;
	}

	public function reset()
	{
		return $this->uninstall(FALSE)
					->install();
	}
}
