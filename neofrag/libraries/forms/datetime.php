<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Datetime extends Date
{
	protected $_datetime_type    = 'datetime';
	protected $_datetime_format  = 'L LT';
	protected $_datetime_size    = 'col-12';
	protected $_datetime_regexp  = '\d{4}(-\d{2}){2} \d{2}(:\d{2}){2}';
	protected $_datetime_printer = 'short_date_time';
}
