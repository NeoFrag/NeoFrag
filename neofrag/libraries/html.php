<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Html extends Library
{
	protected $_tag       = 'div';
	protected $_end_tag   = TRUE;
	protected $_attrs     = [];
	protected $_content   = [];
	protected $_template  = [];
	protected $_container;
	protected $_align;

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

		return $this;
	}

	public function __toString()
	{
		$tag     = $this->_tag;
		$attrs   = $this->_attrs;
		$content = $this->content();

		if ($this->_template)
		{
			foreach ($this->_template as $template)
			{
				call_user_func_array($template, [&$content, &$attrs, &$tag]);
			}
		}

		array_walk($attrs, function(&$value, $key){
			$value = $key.($value !== NULL ? '="'.$value.'"' : '');
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
		return isset($this->_attrs[$name]) ? $this->_attrs[$name] : parent::__get($name);
	}

	public function attr($name, $value = NULL)
	{
		$this->_attrs[$name] = $value;
		return $this;
	}

	public function append_attr($name, $value = NULL, $separator = ' ')
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

	public function content($content = '')
	{
		if (func_get_args())
		{
			$this->_content = is_array($content) ? $content : [$content];
			return $this;
		}

		return implode($this->_content);
	}

	public function prepend($content)
	{
		array_unshift($this->_content, $content);
		return $this;
	}

	public function append($content)
	{
		array_push($this->_content, $content);
		return $this;
	}

	public function tag($tag, $end_tag = TRUE)
	{
		$this->_tag     = $tag;
		$this->_end_tag = $end_tag;

		return $this;
	}

	public function align($align = '')
	{
		if (func_num_args())
		{
			$this->_align = $align;
			return $this;
		}
		else
		{
			return $this->_align;
		}
	}
}
