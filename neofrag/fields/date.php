<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Date extends Field_DateTime
{
	public function raw($value)
	{
		return substr($value->sql(), 0, 10);
	}
}
