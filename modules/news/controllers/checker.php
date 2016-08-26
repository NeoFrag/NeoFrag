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

class m_news_c_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->fix_items_per_page($this->config->news_per_page)->get_data($this->model()->get_news(), $page)];
	}

	public function _tag($tag, $page = '')
	{
		return [$tag, $this->pagination->fix_items_per_page($this->config->news_per_page)->get_data($this->model()->get_news('tag', $tag), $page)];
	}

	public function _category($category_id, $name, $page = '')
	{
		if ($category = $this->model('categories')->check_category($category_id, $name))
		{
			return [$category['title'], $this->pagination->fix_items_per_page($this->config->news_per_page)->get_data($this->model()->get_news('category', $category_id), $page)];
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function _news($news_id, $title)
	{
		if ($news = $this->model()->check_news($news_id, $title))
		{
			return $news;
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}
}

/*
NeoFrag Alpha 0.1
./modules/news/controllers/checker.php
*/