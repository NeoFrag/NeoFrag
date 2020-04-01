<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Time extends Date
{
	protected $_datetime_type    = 'time';
	protected $_datetime_format  = 'LT';
	protected $_datetime_icon    = 'far fa-clock';
	protected $_datetime_regexp  = '\d{2}(:\d{2}){2}';
	protected $_datetime_printer = 'short_time';
}
