<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Array_ extends Library implements \Iterator
{
	protected $_array = [];
	protected $_extends;

	public function __invoke($array = [])
	{
		$this->_array = $array;
		return $this;
	}

	public function get()
	{
		$value = NULL;

		$this->_browse(func_get_args(), -1, function(&$node, $name) use (&$value){
			if (array_key_exists($name, $node))
			{
				$value = $node[$name];
			}
		});

		return $value;
	}

	public function set()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			$node = $value;
		});

		return $this->_extends ?: $this;
	}

	public function append()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			array_push($node, $value);
		});

		return $this->_extends ?: $this;
	}

	public function prepend()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			array_unshift($node, $value);
		});

		return $this->_extends ?: $this;
	}

	public function destroy()
	{
		$this->_browse(func_get_args(), -1, function(&$node, $name){
			unset($node[$name]);
		});

		return $this->_extends ?: $this;
	}

	public function move($id, $move_to)
	{
		$child = $this->_children[$id];
		unset($this->_children[$id]);
		$this->_children = array_slice($this->_children, 0, $move_to, TRUE) + [$id => $child] + array_slice($this->_children, $move_to, NULL, TRUE);
		return $this->_extends ?: $this;
	}

	public function traversal($callback)
	{
		array_walk($this->_children, function($a) use (&$callback){
			if (method_exists($a, 'traversal'))
			{
				$a->traversal($callback);
			}
			else
			{
				$callback($a);
			}
		});

		return $this->_extends ?: $this;
	}

	public function __toArray()
	{
		return $this->_array;
	}

	public function __extends($extends)
	{
		$this->_extends = $extends;
		return $this;
	}

	protected function _browse()
	{
		$args     = func_get_args();
		$callback = array_pop($args);
		$index    = array_shift($args);

		$node = &$this->_array;

		$n = NULL;

		if ($limit = array_shift($args))
		{
			$n = count($index) + $limit;
		}

		$i = 0;

		while (($name = current($index)) && ($n === NULL || $i++ < $n))
		{
			if (!array_key_exists($name, $node))
			{
				$node[$name] = [];
			}

			$node = &$node[$name];

			next($index);
		}

		$callback($node, current($index));
	}

	public function current()
	{
		return current($this->_array);
	}

	public function key()
	{
		return key($this->_array);
	}

	public function next()
	{
		next($this->_array);
	}

	public function rewind()
	{
		reset($this->_array);
	}

	public function valid()
	{
		return key($this->_array) !== NULL;
	}
}
