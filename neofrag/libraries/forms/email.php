<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Email extends Text
{
	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '' && !is_valid_email($post[$this->_name]))
			{
				$this->_errors[] = $this->lang('wrong_email');
			}
		};

		return $this->addon('fa-envelope-o');
	}
}
