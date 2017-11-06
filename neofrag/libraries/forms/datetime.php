<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Datetime extends Date
{
	protected $_datetime_type   = 'datetime';
	protected $_datetime_format = 'L LT';
	protected $_datetime_size   = 'col-md-4';
	protected $_datetime_regexp = '\d{4}(-\d{2}){2} \d{2}(:\d{2}){2}';

	public function value($value)
	{
		$this->_value = $value !== '' ? timetostr($this->lang('%d/%m/%Y %H:%M'), $value) : '';
		return $this;
	}
}
