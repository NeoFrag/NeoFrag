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

class w_breadcrumb_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		$count = count($links = $this->breadcrumb->get_links());
		
		array_walk($links, function(&$value, $key) use ($count){
			$value = '<li'.(($is_last = $key == $count - 1) ? ' class="active"' : '').'><a href="'.url($value[1]).'">'.($is_last && $value[2] !== '' ? icon($value[2]).' ' : '').$value[0].'</a></li>';
		});
		
		return '<ol class="breadcrumb"><li><b>'.$this->config->nf_name.'</b></li>'.implode($links).'</ol>';
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/widgets/breadcrumb/controllers/index.php
*/