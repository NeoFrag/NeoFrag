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

class m_news_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_news(), $page)];
	}
	
	public function _add()
	{
		if (!$this->is_authorized('add_news'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
		
		return [];
	}

	public function _edit($news_id, $title, $tab = 'default')
	{
		if (!$this->is_authorized('modify_news'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
		
		if ($news = $this->model()->check_news($news_id, $title, $tab))
		{
			return $news + [$tab];
		}
	}

	public function delete($news_id, $title)
	{
		if (!$this->is_authorized('delete_news'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		$this->ajax();

		if ($news = $this->model()->check_news($news_id, $title))
		{
			return [$news['news_id'], $news['title']];
		}
	}
	
	public function _categories_add()
	{
		if (!$this->is_authorized('add_news_categories'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
		
		return [];
	}

	public function _categories_edit($category_id, $name)
	{
		if (!$this->is_authorized('modify_news_categories'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		if ($category = $this->model('categories')->check_category($category_id, $name, 'default'))
		{
			return $category;
		}
	}
	
	public function _categories_delete($category_id, $name)
	{
		if (!$this->is_autorized('delete_news_categories'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		$this->ajax();

		if ($category = $this->model('categories')->check_category($category_id, $name, 'default'))
		{
			return [$category_id, $category['title']];
		}
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/news/controllers/admin_checker.php
*/