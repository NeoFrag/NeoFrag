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
	protected $_datetime_size   = 'col-12';
	protected $_datetime_regexp = '\d{4}(-\d{2}){2} \d{2}(:\d{2}){2}';

	public function value($value)
	{
		return parent::value(is_a($value, 'NF\NeoFrag\Libraries\Date') ? $value->short_date_time() : $value);
	}
}
