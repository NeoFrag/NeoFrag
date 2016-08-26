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
 
class m_forum_c_admin_checker extends Controller
{
	public function _edit($forum_id, $title)
	{
		if ($forum = $this->model()->check_forum($forum_id, $title))
		{
			return $forum;
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function delete($forum_id, $title)
	{
		$this->ajax();

		if ($this->model()->check_forum($forum_id, $title))
		{
			return [$forum_id, $title];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function _categories_edit($category_id, $name)
	{
		if ($category = $this->model()->check_category($category_id, $name))
		{
			return $category;
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}
	
	public function _categories_delete($category_id, $name)
	{
		$this->ajax();

		if ($category = $this->model()->check_category($category_id, $name))
		{
			return $category;
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/forum/controllers/admin_checker.php
*/