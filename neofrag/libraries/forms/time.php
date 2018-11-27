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
		return parent::value(is_a($value, 'NF\NeoFrag\Libraries\Date') ? $value->short_time() : $value);
	}
}
