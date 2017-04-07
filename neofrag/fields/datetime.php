<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Datetime
{
	public function init($field)
	{
		$field->default(NeoFrag()->date());
	}

	public function value($value)
	{
		if ($value)
		{
			return NeoFrag()->date($value);
		}
	}

	public function raw($value)
	{
		return $value->sql();
	}
}
