<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Table_Col extends Library
{
	protected $_title;
	protected $_content;
	protected $_compact;
	protected $_align;
	protected $_size;
	protected $_search;
	protected $_sort;

	public function __invoke()
	{
		return $this;
	}

	public function title($title)
	{
		$this->_title = $title;
		return $this;
	}

	public function content($content)
	{
		$this->_content = $content;
		return $this;
	}

	public function compact()
	{
		$this->_compact = TRUE;
		return $this;
	}

	public function align($align)
	{
		$this->_align = $align;
		return $this;
	}

	public function size($size)
	{
		$this->_size = $size;
		return $this;
	}

	public function search($search)
	{
		$this->_search = $search;
		return $this;
	}

	public function display($i, $data)
	{
		return $this->html('td')
					->attr_if($this->_align, 'class', 'text-'.$this->_align)
					->append_attr_if($this->_compact, 'class', 'compact')
					->content($this->execute($i, $data));
	}

	public function execute($i, $data)
	{
		static $output = [];

		if (!array_key_exists($id = spl_object_hash($this).'-'.$i, $output))
		{
			$content = $this->_content;

			if (!is_a($content, 'closure'))
			{
				$content = function($model) use ($content){
					if ($content === NULL)
					{
						return $model;
					}
					else if (method_exists($model, $content))
					{
						return $model->$content();
					}
					else if (isset($model->$content))
					{
						return $model->$content;
					}
				};
			}

			$output[$id] = call_user_func($content, $data);
		}

		return $output[$id];

	}

	public function header()
	{
		return $this->html('th')
					->attr_if($this->_align,          'class', 'text-'.$this->_align)
					->append_attr_if($this->_size,    'class', $this->_size)
					->append_attr_if($this->_compact, 'class', 'compact')
					->content($this->_title);
	}

	public function has_header()
	{
		return $this->_title || $this->_sort;
	}

	public function has_search()
	{
		return $this->_search;
	}

	public function collection($collection, $search, $sort)
	{
		if ($this->_search && $search)
		{
			if (is_a($this->_search, 'closure'))
			{
				$this->_search = call_user_func_array($this->_search, [$collection]);
			}

			$collection->where($this->_search.' LIKE', '%'.$search.'%', 'OR');
		}

		if ($this->_sort)
		{

		}
	}
}
