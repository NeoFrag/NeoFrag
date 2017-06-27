<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Checkbox extends Radio
{
	protected $_type     = 'checkbox';
	protected $_multiple = TRUE;

	protected function _value(&$input, $value)
	{
		$input	->append_attr('name', '[]', '')
				->attr_if(in_array($value, $this->_value), 'checked');
	}
}
