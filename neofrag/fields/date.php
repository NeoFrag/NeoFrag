<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Date extends DateTime
{
	public function raw($value)
	{
		return substr(parent::raw($value), 0, 10);
	}
}
