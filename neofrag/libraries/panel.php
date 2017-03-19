<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Panel extends Library
{
	protected $_heading = [];
	protected $_footer  = [];
	protected $_body;
	protected $_body_tags;
	protected $_color;
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

		return '<div class="panel panel-'.($this->_color ?: 'default').'">
					'.$output.($this->_footer ? $this->button->static_footer($this->_footer)->append_attr('class', 'panel-footer') : '').'
				</div>';
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
		$this->_color = $color;
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

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/panel.php
*/