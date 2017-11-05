<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Panel extends Library
{
	protected $_heading = [];
	protected $_footer  = [];
	protected $_body;
	protected $_body_tags;
	protected $_style;
	protected $_size;

	public function __invoke()
	{
		return $this->reset();
	}

	public function __toString()
	{
		$output = '';

		foreach ($this->_heading as $h)
		{
			$output .= '<h3 class="panel-title">'.$h.'</h3>';
		}

		if ($output)
		{
			$output = '<div class="panel-heading">'.$output.'</div>';
		}

		if ($this->_body)
		{
			$output .= $this->_body_tags ? '<div class="panel-body">'.$this->_body.'</div>' : $this->_body;
		}

		return $this->html()
					->attr('class', 'panel')
					->append_attr('class', $this->_style ?: 'panel-default')
					->content($output)
					->append_content_if($this->_footer, $this->button->static_footer($this->_footer)->append_attr('class', 'panel-footer'))
					->__toString();
	}

	public function title($label = '', $icon = '', $url = '')
	{
		$this->_heading = [];
		return call_user_func_array([$this, 'heading'], func_get_args());
	}

	public function heading($label = '', $icon = '', $url = '')
	{
		if (func_num_args())
		{
			if (!is_a($label, 'Label'))
			{
				$label = $this	->button()
								->title($label)
								->icon($icon)
								->url_if($url, $url);
			}

			$this->_heading[] = $label;
		}
		else if (!empty($this->load->data['module_title']) && !empty($this->load->data['module_icon']))
		{
			$this->_heading[] = $this	->label()
										->title($this->load->data['module_title'])
										->icon($this->load->data['module_icon']);
		}

		return $this;
	}

	public function body($body, $add_body_tags = TRUE)
	{
		$this->_body      = $body;
		$this->_body_tags = $add_body_tags;
		return $this;
	}

	public function footer($footer = '', $align = 'center')
	{
		if (!is_a($footer, 'Button'))
		{
			$footer = $this->button()->title($footer)->align($align);
		}

		$this->_footer[] = $footer;

		return $this;
	}

	public function color($color)
	{
		$this->_style = 'panel-'.$color;
		return $this;
	}

	public function style($style)
	{
		$this->_style = $style;
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
