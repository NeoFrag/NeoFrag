<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

use NF\NeoFrag\Library;

abstract class Labelable extends Library
{
	protected $_title;
	protected $_icon;
	protected $_placeholder;
	protected $_info;
	protected $_size;
	protected $_form;
	protected $_name;
	protected $_value;
	protected $_disabled;
	protected $_read_only;
	protected $_required;
	protected $_template = [];
	protected $_check  = [];
	protected $_filter  = [];
	protected $_errors = [];
	protected $_bind;

	public function __invoke($name)
	{
		$this->_name = $name;

		$this->_template[] = function(&$input){
			$input	->attr_if($id = $this->id(), 'id', $id)
					->attr('name', $this->_name);

			if ($this->_bind)
			{
				$this	->js('form')
						->js('form_bind');

				$input->attr('data-bind');
			}
		};

		$this->_check[] = function($post, &$data){
			if ($this->_disabled || $this->_read_only)
			{
				return FALSE;
			}
		};

		$this->_check[] = function($post, &$data){
			if ($this->_required && (!isset($post[$this->_name]) || $post[$this->_name] === ''))
			{
				$this->_errors[] = $this->lang('Veuillez remplir ce champ');
			}

			if (isset($post[$this->_name]))
			{
				$this->_value = $data[$this->_name] = $post[$this->_name];
			}
		};

		return $this;
	}

	public function __call($name, $args)
	{
		//TODO 5.6 compatibility
		if ($name == 'default')
		{
			return $this->_value;
		}

		return parent::__call($name, $args);
	}

	public function __toString()
	{
		$input = NULL;

		foreach ($this->_template as $template)
		{
			if (call_user_func_array($template, [&$input]) === FALSE)
			{
				break;
			}
		}

		if (!($input = (string)$input) || !$this->_form)
		{
			return $input;
		}

		$display = $this->_form->display();

		return parent	::html()
						->attr('class', 'form-group')
						->append_attr_if($this->_errors, 'class', 'has-danger')
						->append_attr_if($this->_size, 'class', $this->_size)
						->content($this	->array
										->append_if(($label = (string)$this->_label()) && !($display & \NF\NeoFrag\Libraries\Form2::FORM_COMPACT), function() use ($label){
											return parent	::html(($multiple = is_a($this, 'NF\NeoFrag\Libraries\Forms\Multiple')) ? 'legend' : 'label')
															->attr('class', 'col-form-label')
															->attr_if(!$multiple, 'for', $this->_form->token().'_'.$this->_name)
															->content($label);
										})
										->append($input)
										->append_if($this->_errors && ($display & \NF\NeoFrag\Libraries\Form2::FORM_COMPACT), function(){
											return $this->label(implode('<br />', $this->_errors), 'fas fa-exclamation-triangle')->attr('class', 'text-danger');
										})
						)
						->__toString();
	}

	public function id()
	{
		if ($this->_form)
		{
			return $this->_form->token().'_'.$this->_name;
		}
	}

	public function check($post, &$data = [])
	{
		if (is_a($post, 'closure'))
		{
			$callback = $post;

			$this->_check[] = function($post, $data) use (&$callback){
				if ($error = $callback($post, $data))
				{
					$this->_errors[] = $error;
				}
			};

			return $this;
		}
		else
		{
			foreach ($this->_check as $check)
			{
				if ($check($post, $data) === FALSE)
				{
					break;
				}
			}

			return empty($this->_errors);
		}
	}

	public function bind($callback = NULL)
	{
		if (func_num_args())
		{
			$this->_bind = $callback;
		}
		else
		{
			return $this->_bind;
		}

		return $this;
	}

	public function name()
	{
		return $this->_name;
	}

	public function title($title, $icon = NULL)
	{
		$this->_title = $this->lang($title);
		$this->_icon  = $icon;
		return $this;
	}

	public function placeholder($placeholder)
	{
		$this->_placeholder = $this->lang($placeholder);
		return $this;
	}

	public function info($info)
	{
		$this->_info = $this->lang($info);
		return $this;
	}

	public function size($size = '')
	{
		if (func_get_args())
		{
			$this->_size = $size;
			return $this;
		}
		else
		{
			return $this->_size;
		}
	}

	public function form2($form)
	{
		$this->_form = $form;
		return $this;
	}

	public function value($value, $erase = FALSE)
	{
		if ($this->_value === NULL || $erase)
		{
			$this->_value = $value;
		}

		return $this;
	}

	public function disabled()
	{
		$this->_disabled = TRUE;
		return $this;
	}

	public function read_only()
	{
		$this->_read_only = TRUE;
		return $this;
	}

	public function required()
	{
		$this->_required = TRUE;
		return $this;
	}

	public function filter()
	{
		if ($filter = func_get_args())
		{
			$this->_filter = $filter;
			return $this;
		}
		else
		{
			return $this->_filter;
		}
	}

	public function errors()
	{
		return $this->_errors;
	}

	protected function _label()
	{
		$label = $this->label($this->_title, $this->_errors ? 'fas fa-exclamation-triangle' : $this->_icon);

		if ($this->_info || $this->_errors)
		{
			$label	->icon_if(!$this->_errors, $icon = 'fas fa-info-circle text-info')
					->attr('data-toggle',    'popover')
					->attr('data-trigger',   'hover')
					->attr('data-placement', 'auto')
					->attr('data-html',      'true')
					->attr('data-content',   utf8_htmlentities(implode('<br /><br />', array_filter([
						$this->_info   ? $this->label($this->_info, $icon) : '',
						$this->_errors ? $this->label(implode('<br />', $this->_errors), 'fas fa-exclamation-triangle')->attr('class', 'text-danger') : ''
					]))));
		}

		if ($this->_required)
		{
			$label .= '<em>*</em>';
		}

		return $label;
	}

	protected function _placeholder(&$input, $placeholder = 'placeholder')
	{
		$input->attr_if($this->_placeholder, $placeholder, $this->_placeholder);

		if ($this->_form && ($this->_form->display() & \NF\NeoFrag\Libraries\Form2::FORM_COMPACT))
		{
			$input->attr($placeholder, $this->_title ?: $this->_placeholder);
		}
	}
}
