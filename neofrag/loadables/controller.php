<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Controller extends NeoFrag implements \NF\NeoFrag\Loadable
{
	static protected $_objects = [];

	static public function __load($caller, $args = [])
	{
		$name = array_shift($args);

		if (!isset(static::$_objects[$caller_name = get_class($caller)][$name]))
		{
			static::$_objects[$caller_name][$name] = $caller->___load('controllers', $name, [$caller]);
		}

		return static::$_objects[$caller_name][$name];
	}

	public function __construct($caller)
	{
		$this->__caller = $caller;
	}

	public function has_method($name)
	{
		$r = new \ReflectionClass($this);

		try
		{
			$method = $r->getMethod($name);
			return $method->class == ($class = get_class($this)) || substr($class, 0, 2) == 'o_' && substr($class, 2) == $method->class;
		}
		catch (\ReflectionException $error)
		{

		}
	}

	public function is_authorized($action)
	{
		return $this->access($this->__caller->info()->name, $action);
	}
}
