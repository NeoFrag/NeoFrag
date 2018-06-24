<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Text
{
	protected $_size;

	public function __construct($size = NULL)
	{
		$this->_size = $size;
	}

	public function default_()
	{
		return '';
	}

	public function value($value)
	{
		return (string)$value;
	}
}
