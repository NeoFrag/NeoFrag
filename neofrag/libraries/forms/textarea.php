<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Textarea extends Labelable
{
	protected $_rows = 15;

	public function __invoke($name)
	{
		$this->_template[] = function(&$input){
			$input = parent	::html('textarea')
							->attr('class', 'form-control')
							->attr('rows', $this->_rows)
							->attr_if($this->_disabled,  'disabled')
							->attr_if($this->_read_only, 'readonly')
							->content($this->_value);

			$this->_placeholder($input);
		};

		return parent::__invoke($name);
	}

	public function rows($rows)
	{
		$this->_rows = $rows;
		return $this;
	}
}
