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

class Breadcrumb extends Core
{
	private $_links = [];
	
	public function get_links()
	{
		$links = $this->_links;
		
		if (empty($links) && $this->config->segments_url[0] == 'index')
		{
			array_unshift($links, [$this->load->lang('home'), '', 'fa-map-marker']);
		}
		else
		{
			array_unshift($links, [$this->load->module->get_title(), $this->load->module->name == 'pages' ? $this->config->request_url : $this->load->module->name.'.html', $this->load->module->icon ?: 'fa-map-marker']);
		}

		return $links;
	}

	public function __invoke($title = '', $link = '', $icon = '')
	{
		if ($title === '')
		{
			$title = !empty($this->load->module->load->data['module_title']) ? $this->load->module->load->data['module_title'] : '';
		}

		if ($title !== '')
		{
			$this->_links[] = [$title, $link ?: $this->config->request_url, $icon ?: (!empty($this->load->module->load->data['module_icon']) ? $this->load->module->load->data['module_icon'] : $this->load->module->icon)];
		}

		return $this;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/core/breadcrumb.php
*/