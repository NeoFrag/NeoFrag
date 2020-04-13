<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Number extends Text
{
	protected $_type = 'number';
	protected $_step = 1;

	public function __invoke($name)
	{
		parent::__invoke($name);

		array_splice($this->_template, 1, 0, function(&$input){
			$input->attr('step', str_replace(',', '.', $this->_step));
		});

		$this->_check[] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '' && $post[$this->_name] != (float)$post[$this->_name])
			{
				$this->_errors[] = 'Nombre invalide';
			}
		};

		return $this;
	}

	public function value($value, $erase = FALSE)
	{
		return parent::value(str_replace(',', '.', $value), $erase);
	}

	public function step($step)
	{
		$this->_step = $step;
		return $this;
	}
}
