<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Serialized
{
	public function init($field)
	{
		$field->default('');
	}

	public function value($value)
	{
		if (is_a($value, 'NF\NeoFrag\Libraries\Array_'))
		{
			return $value;
		}

		return $value ? NeoFrag()->array(unserialize($value)) : NeoFrag()->array;
	}

	public function raw($value)
	{
		$convert = function(&$value) use (&$convert){
			if (method_exists($value, '__toArray'))
			{
				$value = $value->__toArray();

				array_walk($value, $convert);
			}
			else if (is_a($value, 'NF\NeoFrag\Libraries\Date'))
			{
				$value = $value->sql();
			}
		};

		$convert($value);

		return $value ? serialize($value) : '';
	}
}
