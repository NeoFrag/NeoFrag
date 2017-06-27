<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Date extends Text
{
	protected $_datetime_type   = 'date';
	protected $_datetime_format = 'L';
	protected $_datetime_icon   = 'fa-calendar';
	protected $_datetime_size   = 'col-md-3';
	protected $_datetime_regexp = '\d{4}(-\d{2}){2}';

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[1] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '')
			{
				$data[$this->_name] = $post[$this->_name];
				call_user_func_array($this->_datetime_type.'2sql', [&$data[$this->_name]]);
			}

			if (!isset($data[$this->_name]) || !preg_match('/^'.$this->_datetime_regexp.'$/', $data[$this->_name]))
			{
				$data[$this->_name] = '';

				if ($this->_required)
				{
					$this->_errors[] = $this->lang('required_input');
				}
			}

			$this->value($data[$this->_name]);
		};

		$this->_template[] = function(&$input){
			$this	->css('bootstrap-datetimepicker.min')
					->js('bootstrap-datetimepicker/moment.min')
					->js('bootstrap-datetimepicker/bootstrap-datetimepicker.min')
					->js('bootstrap-datetimepicker/locales/'.$this->config->lang)
					->js_load('$(".input-group.'.$this->_datetime_type.'").datetimepicker({allowInputToggle: true, locale: "'.$this->config->lang.'", format: "'.$this->_datetime_format.'"});');

			$input->append_attr('class', $this->_datetime_type);
		};

		return $this->addon($this->_datetime_icon)
					->size($this->_datetime_size);
	}

	public function value($value)
	{
		$this->_value = $value !== '' ? timetostr($this->lang('date_short'), $value) : '';
		return $this;
	}
}
