<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class Select extends Multiple
{
	const SELECT_MULTIPLE = 1;
	const SELECT_CREATE   = 2;

	protected $_optgroup = [];
	protected $_render;
	protected $_search;

	public function __invoke($name)
	{
		$this->_template[] = function(&$input){
			$encode = function($data){
				array_walk($data, function(&$value, $key){
					$value = array_merge([$key], (array)$value);
				});

				return json_encode(array_values($data));
			};

			$input = parent ::html('select')
							->attr('class', 'form-control selectize')
							->attr('data-options', $encode($this->_data))
							->attr_if($this->_multiple,          'multiple')
							->attr_if($this->_disabled,          'disabled')
							->attr_if(!empty($this->_render[0]), 'data-render-option', $this->_render[0])
							->attr_if($this->_search,            'data-search-field',  $this->_search + 1)
							->attr_if($this->_value,             'data-value',         implode(',', (array)$this->_value));

			if ($this->_optgroup)
			{
				$input	->attr('data-optgroups',      $encode($this->_optgroup[1]))
						->attr('data-optgroup-field', $this->_optgroup[0] + 1)
						->attr_if(!empty($this->_render[1]), 'data-render-optgroup', $this->_render[1]);
			}

			$this	->css('selectize')
						->css('selectize.bootstrap3')
						->js('selectize.min')
						->js('select');

			$this->_placeholder($input, 'data-placeholder');
		};

		parent::__invoke($name);

		$this->_template[] = function(&$input){
			$input->append_attr_if($this->_multiple, 'name', '[]', '');
		};

		return $this;
	}

	public function optgroup($field, $optgroup)
	{
		$this->_optgroup = [$field, $optgroup];
		return $this;
	}

	public function render($render, $optgroup = '')
	{
		$this->_render = [$render, $optgroup];
		return $this;
	}

	public function search($search)
	{
		$this->_search = $search;
		return $this;
	}

	public function multiple($allow_create = FALSE)
	{
		$this->_multiple = self::SELECT_MULTIPLE;

		if ($allow_create)
		{
			$this->_multiple |= self::SELECT_CREATE;
		}

		return $this;
	}
}
