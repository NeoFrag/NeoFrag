<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Field
{
	protected $_fields = [];

	public function __call($name, $args)
	{
		if (preg_match('/^is_(.+)/', $name, $match))
		{
			return isset($this->_fields[$match[1]]);
		}

		if (method_exists($field = $this->_fields[$name] = NeoFrag()->___load('fields', $name, $args), 'init'))
		{
			$field->init($this);
		}

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

	public function raw($value = NULL)
	{
		if (!func_num_args())
		{
			foreach ($this->_fields as $field)
			{
				if (method_exists($field, 'default_'))
				{
					$value = $field->default_($value);
				}
			}
		}

		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'raw'))
			{
				$value = $field->raw($value);
			}
		}

		return $value;
	}

	public function value($model, $value = NULL)
	{
		if (func_num_args() < 2)
		{
			foreach ($this->_fields as $field)
			{
				if (method_exists($field, 'default_'))
				{
					$value = $field->default_($value, $model, $this);
				}
			}
		}

		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'value'))
			{
				$value = $field->value($value, $model, $this);
			}
		}

		return $value;
	}
}
