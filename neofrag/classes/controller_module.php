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

abstract class Controller_Module extends Controller
{
	public function title($title)
	{
		$this->add_data('module_title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->add_data('module_subtitle', $subtitle);
		return $this;
	}

	public function icon($icon)
	{
		$this->add_data('module_icon', $icon);
		return $this;
	}

	public function add_action($url, $title, $icon = '')
	{
		$this->load->caller->add_action($url, $title, $icon);
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/classes/controller_module.php
*/