<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

use NF\NeoFrag\Library;

class Hidden extends Library
{
	protected $_name;
	protected $_value;

	public function __invoke($name, $value)
	{
		$this->_name  = $name;
		$this->_value = $value;
		return $this;
	}

	public function __toString()
	{
		return $this->html('input', TRUE)
					->attr('type',  'hidden')
					->attr('name',  $this->_name)
					->attr('value', $this->_value)
					->__toString();
	}
}
