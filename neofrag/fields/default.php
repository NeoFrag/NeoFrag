<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Default_
{
	protected $_default;

	public function __construct($default)
	{
		$this->_default = $default;
	}

	public function default_($value)
	{
		if ($value === NULL)
		{
			return $this->_default;
		}
	}
}
