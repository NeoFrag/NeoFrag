<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Serialized
{
	public function value($value)
	{
		return $value ? NeoFrag()->array(unserialize($value)) : NeoFrag()->array;
	}

	public function raw($value)
	{
		if (is_a($value, 'NF\NeoFrag\Libraries\Array_'))
		{
			$value = $value->__toArray();
		}

		return $value ? serialize($value) : '';
	}
}
