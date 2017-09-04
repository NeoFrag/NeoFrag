<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Panels;

use NF\NeoFrag\Libraries\Panel;

class Box extends Panel
{
	protected $_color;

	public function __toString()
	{
		$this->css('panel-box');

		return '<div class="small-box '.$this->_color.'">
					<div class="inner">
						<h3>'.$this->_body.'</h3>
						<p>'.$this->_heading[0]->title().'</p>
					</div>
					<div class="icon">'.$this->_heading[0]->icon().'</div>
					<a class="small-box-footer" href="'.$this->_heading[0]->url().'">
						'.$this->_footer[0]->title().'
					</a>
				</div>';
	}

	public function color($color)
	{
		$this->_color = $color;
		return $this;
	}

}
