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

class m_error_c_index extends Controller_Module
{
	public function index()
	{
		header('HTTP/1.0 404 Not Found');

		$this->title($this->load->object->get_title($this('unfound')));

		return [
			$this	->panel()
					->heading($this('unfound'), 'fa-warning')
					->body($this('page_unfound'))
					->color('danger'),
			$this->panel_back()
		];
	}

	public function unauthorized()
	{
		header('HTTP/1.0 401 Unauthorized');

		$this->title($this->load->object->get_title($this('unauthorized')));

		return [
			$this	->panel()
					->heading($this('unauthorized'), 'fa-warning')
					->body($this('required_permissions'))
					->color('danger'),
			$this->panel_back()
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/error/controllers/index.php
*/