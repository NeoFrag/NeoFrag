<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Childrenable extends Library
{
	protected $_id;
	protected $_children = [];

	public function __invoke()
	{
		foreach (func_get_args() as $child)
		{
			if (is_array($child))
			{
				$this->_children = array_merge($this->_children, $child);
			}
			else
			{
				$this->_children[] = $child;
			}
		}
		return $this->reset();
	}

	public function id($id)
	{
		$this->_id = $id;
		return $this;
	}

	public function children()
	{
		return $this->_children;
	}

	public function prepend($child)
	{
		array_unshift($this->_children, $child);
		return $this;
	}

	public function append($child)
	{
		array_push($this->_children, $child);
		return $this;
	}

	public function delete($id)
	{
		unset($this->_children[$id]);
		return $this;
	}

	public function move($id, $move_to)
	{
		$child = $this->_children[$id];
		unset($this->_children[$id]);
		$this->_children = array_slice($this->_children, 0, $move_to, TRUE) + [$id => $child] + array_slice($this->_children, $move_to, NULL, TRUE);
		return $this;
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
		return $this;
	}
}
