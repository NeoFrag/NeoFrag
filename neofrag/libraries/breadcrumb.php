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

class Breadcrumb extends Library
{
	private $_links = [];
	
	public function get_links()
	{
		$links = $this->_links;
		
		if (empty($links) && $this->url->segments[0] == 'index')
		{
			array_unshift($links, [$this->lang('home'), '', 'fa-map-marker']);
		}
		else
		{
			array_unshift($links, [NeoFrag()->module->get_title(), NeoFrag()->module->name == 'pages' ? $this->url->request : NeoFrag()->module->name, NeoFrag()->module->icon ?: 'fa-map-marker']);
		}

		return $links;
	}

	public function __invoke($title = '', $link = '', $icon = '')
	{
		if ($title === '')
		{
			$title = !empty(NeoFrag()->module->load->data['module_title']) ? NeoFrag()->module->load->data['module_title'] : '';
		}

		if ($title !== '')
		{
			$this->_links[] = [$title, $link ?: $this->url->request, $icon ?: (!empty(NeoFrag()->module->load->data['module_icon']) ? NeoFrag()->module->load->data['module_icon'] : NeoFrag()->module->icon)];
		}

		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/breadcrumb.php
*/