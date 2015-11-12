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
	public function index($config = array())
	{
		$links = array(
			'<b>'.$this->config->nf_name.'</b>&nbsp;&nbsp;&nbsp;'.icon('fa-angle-right').'&nbsp;&nbsp;&nbsp;'.($this->config->segments_url[0] == 'index' ? icon('fa-map-marker').' ' : '').$this('home') => url()
		);
		
		if ($this->config->segments_url[0] != 'index')
		{
			$links[icon(NeoFrag::loader()->module->icon ?: 'fa-map-marker').' '.NeoFrag::loader()->module->get_title()] = url(NeoFrag::loader()->module->name.'.html');
		}
		
		$names = array_keys($links);
		$last = end($names);
		
		array_walk($links, function(&$url, $name) use ($last){
			$url = '<li'.($name == $last ? ' class="active"' : '').'><a href="'.$url.'">'.$name.'</a></li>';
		});
		
		return '<ol class="breadcrumb">'.implode($links).'</ol>';
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/widgets/breadcrumb/controllers/index.php
*/