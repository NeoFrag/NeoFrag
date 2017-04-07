<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Enum
{
	protected $_values;

	public function __construct()
	{
		$this->_values = func_get_args();
	}

	public function raw($value)
	{
		return (string)$value;
	}
}
