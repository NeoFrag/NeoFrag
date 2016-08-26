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

abstract class Controller_Widget extends Controller
{
	abstract public function index($config = []);

	public function title($title)
	{
		$this->add_data('widget_title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->add_data('widget_subtitle', $subtitle);
		return $this;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/classes/controller_widget.php
*/