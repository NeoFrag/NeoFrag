<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Model extends NeoFrag implements \NF\NeoFrag\Loadable
{
	static protected $_objects = [];

	static public function __load($caller, $args = [])
	{
		$name = array_shift($args) ?: $caller->info()->name;

		if (!isset(static::$_objects[$caller_name = get_class($caller)][$name]))
		{
			static::$_objects[$caller_name][$name] = $caller->___load('models', $name, [$caller]);
		}

		return static::$_objects[$caller_name][$name];
	}

	public function __construct($caller)
	{
		$this->__caller = $caller;
	}
}
