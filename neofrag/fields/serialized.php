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
		return $value ? unserialize($value) : NULL;
	}

	public function raw($value)
	{
		return $value ? serialize($value) : '';
	}
}
