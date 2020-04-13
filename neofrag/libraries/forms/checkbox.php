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
				->attr_if(in_array($value, $this->_value ?: []), 'checked');
	}

	public function value($value, $erase = FALSE)
	{
		if (is_bool($value) && count($this->_data) == 1 && $value)
		{
			$value = [array_keys($this->_data)[0]];
		}

		return parent::value($value, $erase);
	}
}
