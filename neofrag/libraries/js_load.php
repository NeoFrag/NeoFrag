<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Js_Load extends Library
{
	protected $_script;

	public function __invoke($script)
	{
		$this->_script = $script;

		$this->output->data->append('js_load', $this);

		return $this;
	}

	public function __toString()
	{
		return (string)$this->_script;
	}
}
