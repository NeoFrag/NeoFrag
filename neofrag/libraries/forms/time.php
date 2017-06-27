<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Time extends Date
{
	protected $_datetime_type   = 'time';
	protected $_datetime_format = 'LT';
	protected $_datetime_icon   = 'fa-clock-o';
	protected $_datetime_regexp = '\d{2}(:\d{2}){2}';

	public function value($value)
	{
		$this->_value = $value !== '' && $value !== '00:00:00' ? timetostr($this->lang('time_short'), $value) : '';
		return $this;
	}
}
