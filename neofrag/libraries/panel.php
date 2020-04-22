<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Panel extends Library
{
	protected $_heading = [];
	protected $_footer  = [];
	protected $_data    = [];
	protected $_body;
	protected $_body_tags;
	protected $_style;
	protected $_size;

	public function __invoke()
	{
		return $this;
	}

	public function __toString()
	{
		$output = '';

		if ($this->_heading)
		{
			$headers = $this->_heading;

			$headers[] = array_shift($headers);

			$output = $this	->button
							->static_footer($headers, 'left')
							->append_attr('class', 'card-header')
							->tag('h6');
		}

		if ($this->_body)
		{
			$output .= $this->_body_tags ? '<div class="card-body">'.$this->_body.'</div>' : $this->_body;
		}

		$table = $this	->html()
						->attr('class', 'card')
						->append_attr_if($this->_style, 'class', $this->_style)
						->content($output)
						->append_if($this->_footer, $this->button->static_footer($this->_footer)->append_attr('class', 'card-footer'));

		foreach ($this->_data as $key => $value)
		{
			$table->attr('data-'.$key, $value);
		}

		return (string)$table;
	}

	public function title($label = '', $icon = '')
	{
		if ($this->_heading)
		{
			$this->_heading[0]	->title_if($label, $label)
								->icon_if($icon, $icon);
		}
		else
		{
			$this->heading($label, $icon);
		}

		return $this;
	}

	public function heading($label = '', $icon = '', $url = '')
	{
		if (func_num_args())
		{
			if (!is_a($label, 'NF\NeoFrag\Libraries\Html'))
			{
				$label = $this	->button()
								->title($label)
								->icon($icon)
								->url_if($url, $url);
			}

			$this->_heading[] = $label;
		}
		else if ($this->__caller == $this->output->module())
		{
			$this->_heading[] = $this	->label()
										->title($this->output->data->get('module', 'title'))
										->icon($this->output->data->get('module', 'icon'));
		}
		else
		{
			$this->_heading[] = $this	->label()
										->title($this->__caller->info()->title)
										->icon($this->__caller->info()->icon);
		}

		return $this;
	}

	public function create($name)
	{
		if (($model = @$this->model2($name)) && ($action = @$model->action('create')))
		{
			$this->_heading[] = $action->__button()->align('right');
		}

		return $this;
	}

	public function body($body = '', $add_body_tags = TRUE)
	{
		if (func_get_args())
		{
			$this->_body      = $body;
			$this->_body_tags = $add_body_tags;
			return $this;
		}
		else
		{
			return $this->_body;
		}
	}

	public function footer($footer = '', $align = 'center')
	{
		if (!is_a($footer, 'NF\NeoFrag\Libraries\Html'))
		{
			$footer = $this	->button()
							->title($footer)
							->align($align);
		}

		$this->_footer[] = $footer;

		return $this;
	}

	public function color($color)
	{
		$this->_style = 'border-'.$color;
		return $this;
	}

	public function style($style)
	{
		$this->_style = $style;
		return $this;
	}

	public function data($data, $value = '')
	{
		if (func_num_args() == 2)
		{
			$this->_data[$data] = $value;
		}
		else
		{
			$this->_data = $data;
		}

		return $this;
	}

	public function size($size = '')
	{
		if (func_num_args())
		{
			$this->_size = $size;
			return $this;
		}
		else
		{
			return $this->_size;
		}
	}
}
