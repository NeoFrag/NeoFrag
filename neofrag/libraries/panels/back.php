<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Panel_back extends Panel
{
	private $_url;

	public function __invoke($url = '')
	{
		$this->_url = $url;
		return $this->reset();
	}

	public function __toString()
	{
		return $this->html()
					->attr('class', 'panel panel-back')
					->content($this->button_back($this->_url))
					->__toString();
	}
}
