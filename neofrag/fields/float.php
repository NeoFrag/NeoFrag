<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Float_
{
	public function value($value)
	{
		return (float)$value;
	}

	public function raw($value)
	{
		return (float)$value;
	}
}
