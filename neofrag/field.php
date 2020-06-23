<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Field
{
	protected $_fields = [];
	protected $_nullable;
	protected $_default;

	public function __call($name, $args)
	{
		if ($name == 'default')
		{
			$this->_default = $args[0];
		}
		else
		{
			if (preg_match('/^is_(.+)/', $name, $match))
			{
				return isset($this->_fields[$match[1]]);
			}

			if (method_exists($field = $this->_fields[$name] = NeoFrag()->___load('fields', $name, $args), 'init'))
			{
				$field->init($this);
			}
		}

		return $this;
	}

	public function null()
	{
		$this->_nullable = TRUE;
		return $this;
	}

	public function key($key)
	{
		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'key'))
			{
				$key = $field->key($key);
			}
		}

		return $key;
	}

	public function raw($value)
	{
		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'raw') && (!$this->_nullable || $value !== NULL))
			{
				$value = $field->raw($value, $this->_nullable);
			}
		}

		return $value;
	}

	public function value($model, $value)
	{
		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'value') && ($this->is_depends() || !$this->_nullable || $value !== NULL))
			{
				$value = $field->value($value, $model, $this);
			}
		}

		return $value;
	}

	public function init()
	{
		return $this->_default !== NULL && !$this->_nullable ? $this->_default : NULL;
	}
}
