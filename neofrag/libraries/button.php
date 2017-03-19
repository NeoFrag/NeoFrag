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

class Button extends Label
{
	protected $_compact = FALSE;
	protected $_outline = FALSE;
	protected $_style   = [];
	protected $_data    = [];

	static public function footer($buttons, $align = 'center')
	{
		$output = NeoFrag()->html();

		if (($n = count($buttons)) > 1)
		{
			$footers = [];

			foreach ($buttons as $footer)
			{
				$footers[$footer->align() ?: $align][] = $footer;
			}

			array_walk($footers, function(&$footer, $align){
				$footer = NeoFrag()	->html()
									->attr('class', 'text-'.$align)
									->content($footer);
			});

			$output->content($footers);
		}
		else if ($n)
		{
			$output	->attr('class', 'text-'.($buttons[0]->align() ?: $align))
					->content($buttons[0]);
		}

		return $output;
	}

	public function __invoke()
	{
		parent::__invoke();

		$this->_template[] = function(&$content, &$attrs, &$tag){
			foreach ($this->_data as $key => $value)
			{
				$attrs['data-'.$key] = $value;
			}

			$class = [];

			if ($this->_color || $this->_compact || $this->_outline)
			{
				$class[] = 'btn';
				$class[] = 'btn-'.($this->_color ?: 'default');

				if ($this->_compact)
				{
					$class[] = 'btn-xs';
				}

				if ($this->_outline)
				{
					$class[] = 'btn-outline';
				}
			}

			if ($this->_style)
			{
				$class = array_merge($class, array_filter($this->_style, 'is_string'));

				$style = implode(';', array_map(function($a){
					return implode(': ', $a);
				}, array_filter($this->_style, 'is_array')));

				if ($style)
				{
					$attrs['style'] = $style;
				}
			}

			$attrs['class'] = implode(' ', $class);
		};

		return $this->reset();
	}

	public function compact()
	{
		$this->_compact = TRUE;
		return $this;
	}

	public function outline()
	{
		$this->_outline = TRUE;
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

	public function style($style, $value = '')
	{
		if (func_num_args() == 2)
		{
			$this->_style[] = [$style, $value];
		}
		else
		{
			$this->_style = array_merge($this->_style, explode(' ', $style));
		}

		return $this;
	}

	public function modal($modal)
	{
		return $this->url('#')
					->data([
						'toggle' => 'modal',
						'target' => '#'.$modal->id,
					]);
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/button.php
*/