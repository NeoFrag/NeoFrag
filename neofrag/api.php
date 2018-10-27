<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Api
{
	protected $_controller;

	public function __construct($controller)
	{
		$this->_controller = $controller;
	}

	public function __call($name, $args)
	{
		ob_start();
		$result = call_user_func_array([$this->_controller, $name], $args);
		ob_end_clean();

		return $result;
	}
}
