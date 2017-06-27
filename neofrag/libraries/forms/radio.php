<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Radio extends Multiple
{
	protected $_type = 'radio';
	protected $_inline;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_template[0] = function(&$input){
			$output = [];

			foreach ($this->_data as $value => $label)
			{
				$input = $this	->html('input', TRUE)
								->attr('type',  $this->_type)
								->attr('name',  $this->_name)
								->attr('value', $value)
								->attr_if($this->_disabled, 'disabled')
								->attr_if($this->_read_only, 'readonly');

				$this->_value($input, $value);

				$output[] = '<div class="'.$this->_type.($this->_inline || ($this->_form->display() & \NF\NeoFrag\Libraries\Form2::FORM_INLINE) ? '-inline' : '').'">
								<label>
									'.$input.'&nbsp;'.$label.'
								</label>
							</div>';
			}

			$input = implode($output);
		};

		return $this;
	}

	public function inline()
	{
		$this->_inline = TRUE;
		return $this;
	}

	protected function _value(&$input, $value)
	{
		$input->attr_if($this->_value == $value, 'checked');
	}
}
