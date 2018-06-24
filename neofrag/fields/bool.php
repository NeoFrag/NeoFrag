<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Bool_
{
	public function value($value)
	{
		return (bool)$value;
	}

	public function raw($value)
	{
		return (string)(int)$value;
	}
}
