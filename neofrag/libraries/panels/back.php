<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Panels;

use NF\NeoFrag\Libraries\Panel;

class Back extends Panel
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
