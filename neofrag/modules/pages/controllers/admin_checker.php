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

class m_pages_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_pages(), $page)];
	}
	
	public function _edit($page_id, $title, $tab = 'default')
	{
		if ($page = $this->model()->check_page($page_id, $title, $tab, TRUE))
		{
			return $page + [$tab];
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}
	
	public function delete($page_id, $title)
	{
		$this->ajax();

		if ($page = $this->model()->check_page($page_id, $title, 'default', TRUE))
		{
			return [$page['page_id'], $page['title']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/pages/controllers/admin_checker.php
*/