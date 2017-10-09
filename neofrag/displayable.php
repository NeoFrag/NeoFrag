<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Displayable extends NeoFrag
{
	protected $_id;
	protected $_children;

	public function __construct()
	{
		$this->_children = $this->array()->__extends($this);
	}

	public function __call($name, $args)
	{
		if (is_object($this->_children))
		{
			return call_user_func_array([$this->_children, $name], $args);
		}
		else
		{
			return parent::__call($name, $args);
		}
	}

	public function __toString()
	{
		return implode($this->_children->__toArray());
	}

	public function __wakeup()
	{
		if (is_array($this->_children))
		{
			$this->_children = $this->array($this->_children)->__extends($this);
		}
	}

	public function id($id)
	{
		$this->_id = $id;
		return $this;
	}
}
