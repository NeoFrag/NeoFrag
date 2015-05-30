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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Panel_Box extends Panel
{
	public function display($id = NULL)
	{
		NeoFrag::loader()->css('neofrag.panel-box');
		
		return '<div class="small-box '.$this->color.'">
					<div class="inner">
						<h3>'.$this->count.'</h3>
						<p>'.$this->label.'</p>
					</div>
					'.($this->icon ? '<div class="icon">'.NeoFrag::loader()->assets->icon($this->icon).'</div>' : '').'
					<a class="small-box-footer" href="'.$this->url.'">
						'.$this->footer.'
					</a>
				</div>';
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/classes/panel_box.php
*/