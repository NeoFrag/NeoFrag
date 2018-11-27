<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Radio extends Multiple
{
	protected $_type   = 'radio';
	protected $_inline = TRUE;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_template[0] = function(&$input){
			$output = [];

			$i = 0;

			foreach ($this->_data as $value => $label)
			{
				$input = $this	->html('input', TRUE)
								->attr('class', 'form-check-input')
								->attr('type',  $this->_type)
								->attr('id',    $id = implode('_', [$this->_form->token(), $this->_name, ++$i]))
								->attr('name',  $this->_name)
								->attr('value', $value)
								->attr_if($this->_disabled, 'disabled')
								->attr_if($this->_read_only, 'readonly');

				$this->_value($input, $value);

				if ($this->_bind)
				{
					$this	->js('form')
							->js('form_bind');

					$input->attr('data-bind');
				}

				$output[] = '<div class="form-check'.($this->_inline || ($this->_form->display() & \NF\NeoFrag\Libraries\Form2::FORM_INLINE) ? ' form-check-inline' : '').'">
								'.$input.'
								<label class="form-check-label" for="'.$id.'">'.$label.'</label>
							</div>';
			}

			$input = implode($output);
		};

		return $this;
	}

	public function inline($inline)
	{
		$this->_inline = $inline;
		return $this;
	}

	protected function _value(&$input, $value)
	{
		$input->attr_if((string)$this->_value === (string)$value, 'checked');
	}
}
