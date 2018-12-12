<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Array_ extends Library implements \Iterator, \ArrayAccess
{
	protected $_array = [];
	protected $_extends;

	public function __invoke($array = [])
	{
		if (func_num_args() > 1)
		{
			$array = func_get_args();
		}
		else if (is_a($array, 'NF\NeoFrag\Libraries\Array_') && !is_a($this, 'NF\NeoFrag\Displayable'))
		{
			return $array;
		}

		if (!is_array($array))
		{
			$array = [$array];
		}

		$this->_array = $array;

		return $this;
	}

	public function __call($name, $args)
	{
		if ($name == 'empty')
		{
			return empty($this->_array);
		}

		return parent::__call($name, $args);
	}

	public function __get($name)
	{
		if (is_array($value = $this->get($name)))
		{
			$value = NeoFrag()->array($value);
		}

		return $value;
	}

	public function __isset($name)
	{
		return $this->get($name) !== NULL;
	}

	public function index($key = NULL)
	{
		$list = [];
		$n = 0;

		foreach ($this->_array as $row)
		{
			if (is_a($row, 'NF\NeoFrag\Loadables\Model2'))
			{
				$list[$row->{$key === NULL ? 'id' : $key}] = $row;
			}
			else
			{
				if ($key === NULL)
				{
					$id = array_shift($row);
				}
				else
				{
					$id = $row[$key];
					unset($row[$key]);
				}

				if (!$n)
				{
					$n = count($row);
				}

				$list[$id] = $n == 1 ? array_shift($row) : array_values($row);
			}
		}

		$this->_array = $list;

		return $this->_extends ?: $this;
	}

	public function count()
	{
		return count($this->_array);
	}

	public function get()
	{
		if (!$args = func_get_args())
		{
			return $this->_array;
		}

		$value = NULL;

		$this->_browse($args, -1, function(&$node, $name) use (&$value){
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

	public function merge()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			if ($node !== NULL)
			{
				$node = array_merge($node, array_filter($value, function($a){
					return $a !== '';
				}));
			}
			else
			{
				$node = $value;
			}
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

	public function shift()
	{
		return array_shift($this->_array);
	}

	public function pop()
	{
		return array_pop($this->_array);
	}

	public function destroy()
	{
		$this->_browse(func_get_args(), -1, function(&$node, $name){
			unset($node[$name]);
		});

		return $this->_extends ?: $this;
	}

	public function move()
	{
		$args    = func_get_args();
		$move_to = array_pop($args);

		$this->_browse($args, -1, function(&$node, $name) use ($move_to){
			$child = $node[$name];
			unset($node[$name]);
			$node = array_slice($node, 0, $move_to, TRUE) + [$name => $child] + array_slice($node, $move_to, NULL, TRUE);
		});

		return $this->_extends ?: $this;
	}

	public function reverse($preserve_keys = FALSE)
	{
		$this->_array = array_reverse($this->_array, $preserve_keys);
		return $this->_extends ?: $this;
	}

	public function sort($sort)
	{
		if (func_num_args() == 2)
		{
			list($callback, $user_callback) = func_get_args();

			$sort = function($a, $b) use ($callback, $user_callback){
				return $callback($user_callback($a), $user_callback($b));
			};
		}

		usort($this->_array, $sort);
		return $this->_extends ?: $this;
	}

	public function each($callback)
	{
		array_walk($this->_array, function(&$a, $key) use (&$callback){
			if (is_string($callback))
			{
				if (method_exists($a, $callback))
				{
					$a = $a->$callback();
				}
				else
				{
					$a = $callback($a);
				}
			}
			else
			{
				$a = $callback($a, $key);
			}
		});

		return $this->_extends ?: $this;
	}

	public function filter($callback = NULL)
	{
		if (is_string($callback))
		{
			$not = FALSE;

			if ($callback[0] == '!')
			{
				$callback = substr($callback, 1);
				$not = TRUE;
			}

			$this->_array = array_filter($this->_array, function(&$a, $key) use (&$callback, $not){
				return (method_exists($a, $callback) ? $a->$callback($key) : $callback($a, $key)) == !$not;
			}, ARRAY_FILTER_USE_BOTH);
		}
		else if ($callback)
		{
			$this->_array = array_filter($this->_array, $callback, ARRAY_FILTER_USE_BOTH);
		}
		else
		{
			$this->_array = array_filter($this->_array);
		}

		return $this->_extends ?: $this;
	}

	public function unique()
	{
		$args = func_get_args();

		$this->_browse($args, -1, function(&$node, $name){
			$node = array_unique($node, SORT_REGULAR);
		});

		return $this->_extends ?: $this;
	}

	public function sum($callback = NULL)
	{
		$sum = 0;

		array_walk($this->_array, function($a) use (&$sum){
			$sum += $a;
		});

		return $callback ? $callback($sum) : $sum;
	}

	public function values()
	{
		return array_values($this->_array);
	}

	public function keys()
	{
		return array_keys($this->_array);
	}

	public function last_key()
	{
		return array_last_key($this->_array);
	}

	public function implode($glue = '')
	{
		return implode($glue, $this->_array);
	}

	public function __toString()
	{
		return $this->implode();
	}

	public function __toArray($recursive = TRUE)
	{
		$array = $this->_array;

		if ($recursive)
		{
			array_walk_recursive($array, function(&$item){
				if (method_exists($item, '__toArray'))
				{
					$item = $item->__toArray();
				}
			});
		}

		return $array;
	}

	public function __sleep()
	{
		return ['_array'];
	}

	public function __extends($extends = NULL)
	{
		if ($extends)
		{
			$this->_extends = $extends;
			return $this;
		}
		else
		{
			return $this->_extends ?: $this;
		}
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

		while (($name = current($index)) !== FALSE && ($n === NULL || $i++ < $n))
		{
			if (!array_key_exists($name, $node))
			{
				$node[$name] = [];
			}

			if (is_a($node[$name], 'NF\NeoFrag\Libraries\Array_'))
			{
				$node = &$node[$name]->_array;
			}
			else
			{
				$node = &$node[$name];
			}

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
		return array_key_exists($this->key(), $this->_array);
	}

	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->_array[] = $value;
		}
		else
		{
			$this->_array[$offset] = $value;
		}
	}

	public function offsetExists($offset)
	{
		return isset($this->_array[$offset]);
	}

	public function offsetUnset($offset)
	{
		unset($this->_array[$offset]);
	}

	public function offsetGet($offset)
	{
		if (array_key_exists($offset, $this->_array))
		{
			return $this->_array[$offset];
		}
	}
}
