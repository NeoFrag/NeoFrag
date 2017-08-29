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

		foreach ($this->_heading as $h)
		{
			$output .= '<h4 class="card-title">'.$h.'</h4>';
		}

		if ($output)
		{
			$output = '<div class="card-header">'.$output.'</div>';
		}

		if ($this->_body)
		{
			$output .= $this->_body_tags ? '<div class="card-body">'.$this->_body.'</div>' : $this->_body;
		}

		return $this->html()
						->attr('class', 'card')
						->append_attr_if($this->_style, 'class', $this->_style)
						->content($output)
					->append_content_if($this->_footer, $this->button->static_footer($this->_footer)->append_attr('class', 'card-footer'))
					->__toString();
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
			if (!is_a($label, 'NF\NeoFrag\Libraries\Label'))
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
		if (!is_a($footer, 'NF\NeoFrag\Libraries\Button'))
		{
			$footer = $this->button()->title($footer)->align($align);
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
