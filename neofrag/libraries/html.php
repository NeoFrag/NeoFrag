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

class Html extends Library
{
	protected $_tag       = 'div';
	protected $_end_tag   = TRUE;
	protected $_attrs     = [];
	protected $_content   = '';
	protected $_template  = [];
	protected $_container;

	public function __invoke()
	{
		$args = func_get_args();

		if (func_num_args())
		{
			$this->_tag = $args[0];

			if (isset($args[1]))
			{
				$this->_end_tag = $args[1];
			}
		}

		return $this->reset();
	}

	public function __toString()
	{
		$tag       = $this->_tag;
		$attrs     = $this->_attrs;
		$content   = $this->_content;

		if ($this->_template)
		{
			foreach ($this->_template as $template)
			{
				call_user_func_array($template, [&$content, &$attrs, &$tag]);
			}
		}

		if (is_array($content))
		{
			$content = implode($content);
		}

		array_walk($attrs, function(&$value, $key){
			$value = $key.($value != '' ? '="'.utf8_htmlentities($value).'"' : '');
		});

		$content = '<'.implode(' ', array_merge([$tag], $attrs)).'>'.($content || $this->_end_tag ? $content.'</'.$tag.'>' : '');

		if ($this->_container)
		{
			$content = call_user_func_array($this->_container, [$content])->__toString();
		}

		return $content;
	}

	public function __get($name)
	{
		if ($name == 'content')
		{
			return $this->_content;
		}

		return isset($this->_attrs[$name]) ? $this->_attrs[$name] : parent::__get($name);
	}

	public function attr($name, $value = '')
	{
		$this->_attrs[$name] = $value;
		return $this;
	}

	public function append_attr($name, $value, $separator = ' ')
	{
		if (empty($this->_attrs[$name]))
		{
			$this->_attrs[$name] = $value;
		}
		else
		{
			$this->_attrs[$name] .= $separator.$value;
		}

		return $this;
	}

	public function content($content)
	{
		$this->_content = $content;
		return $this;
	}

	public function append_content($content)
	{
		$this->_content .= $content;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6.1
./neofrag/libraries/html.php
*/