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
		$field->default(NeoFrag()->date()->sql());
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
		if (is_a($value, 'NF\NeoFrag\Libraries\Date'))
		{
			$value = $value->sql();
		}

		return $value;
	}
}
