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
	protected $_style = [];
	protected $_align;
	protected $_size;
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
		return $this->style('compact');
	}

	public function style($style)
	{
		$this->_style[] = $style;
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

	public function sort($sort, $db = NULL)
	{
		$this->_sort = [$sort, $db];
		return $this;
	}

	public function display($table, $i, $data)
	{
		return $this->html('td')
					->attr_if($this->_align, 'class', 'text-'.$this->_align)
					->append_attr_if($this->_style, 'class', implode(' ', $this->_style))
					->content($this->execute($table, $i, $data));
	}

	public function execute($table, $i, $data)
	{
		static $output = [];

		if (!array_key_exists($id = $table.spl_object_hash($this).'-'.$i, $output))
		{
			$content = $this->_content;

			if (!is_a($content, 'closure'))
			{
				$content = function($model) use ($content){
					if ($content == '#id' && !empty($model->id))
					{
						return '#'.$model->id;
					}
					else
					{
						$callback = NULL;
						$force    = FALSE;
						$value    = '';

						if (preg_match('/^(.+?)\((.+?)\)$/', $content, $match) && function_exists($match[1]))
						{
							list(, $callback, $content) = $match;
						}

						if (preg_match('/^->(.+)/', $content, $match))
						{
							$force = TRUE;
							$content = $match[1];
						}

						if ($content === NULL)
						{
							$value = $model;
						}
						else if (!$force && method_exists($model, $content))
						{
							$value = $model->$content();
						}
						else if (isset($model->$content))
						{
							$value = $model->$content;
						}
						else if (is_array($model) && array_key_exists($content, $model))
						{
							$value = $model[$content];
						}

						if ($callback)
						{
							$value = call_user_func($callback, $value);
						}

						return $value;
					}
				};
			}

			$output[$id] = call_user_func($content, $data);
		}

		return $output[$id];

	}

	public function header($sorts, $i)
	{
		$header = $this->_title;

		if ($this->_sort)
		{
			$header = $header ?: icon('fas fa-sort-amount-down');

			if (array_key_exists($i, $sorts))
			{
				$header .= icon($sorts[$i] == 'desc' ? 'fas fa-caret-down' : 'fas fa-caret-up');

				if (count($sorts) > 1)
				{
					$header .= '<small>'.(array_search($i, array_keys($sorts)) + 1).'</small>';
				}
			}

			$header = '<a href="#" data-col="'.$i.'">'.$header.'</a>';
		}

		return $this->html('th')
					->attr_if($this->_align,        'class', 'text-'.$this->_align)
					->append_attr_if($this->_size,  'class', $this->_size)
					->append_attr_if($this->_style, 'class', implode(' ', $this->_style))
					->content($header);
	}

	public function has_header()
	{
		return $this->_title || $this->_sort;
	}

	public function collection($collection, $sort_by)
	{
		if ($sort_by && $this->_sort)
		{
			if (is_a($this->_sort[1], 'closure'))
			{
				call_user_func_array($this->_sort[1], [$collection]);
			}

			$output = [];

			foreach (strtoarray(', ', $this->_sort[0]) as $sort)
			{
				if (preg_match('/^(.+?)(?: (ASC|DESC))?$/', $sort, $match))
				{
					$output[] = $match[1].' '.strtoupper($match[2] == 'DESC' ? ($sort_by == 'desc' ? 'asc' : 'desc') : $sort_by);
				}
				else
				{
					$output[] = $sort.' '.strtoupper($sort_by);
				}
			}

			return implode(', ', $output);
		}
	}
}
