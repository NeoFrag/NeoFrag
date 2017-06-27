<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Number extends Text
{
	protected $_type = 'number';

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '' && ($post[$this->_name] != (int)$post[$this->_name] || $post[$this->_name] != (float)$post[$this->_name]))
			{
				$this->_errors[] = 'Nombre invalide';
			}
		};

		return $this->size('col-md-2');
	}

	public function value($value)
	{
		$this->_value = str_replace(',', '.', $value);
		return $this;
	}
}
