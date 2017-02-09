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

class Panel_Box extends Panel
{
	public function __toString()
	{
		$this->css('neofrag.panel-box');

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
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/panel_box.php
*/