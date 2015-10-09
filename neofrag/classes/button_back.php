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

class Button_back
{
	private $_url;
	private $_title;
	
	public function __construct($url = '', $title = '')
	{
		$this->_url   = NeoFrag::loader()->session->get_back() ?: $url;
		$this->_title = $title;
	}

	public function display($id = NULL)
	{
		return '<div class="panel panel-back"><a class="btn btn-default" href="'.url($this->_url).'">'.($this->_title ?: NeoFrag::loader()->lang('back')).'</a></div>';
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/classes/button_back.php
*/