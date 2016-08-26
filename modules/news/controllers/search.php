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

class m_news_c_search extends Controller
{
	public function index($result, $keywords)
	{
		$result['introduction'] = highlight($result['introduction']."\r\r".$result['content'], $keywords);
		return $this->load->view('search/index', $result);
	}

	public function detail($result, $keywords)
	{
		$result['introduction'] = highlight($result['introduction']."\r\r".$result['content'], $keywords, 1024);
		return $this->load->view('search/index', $result);
	}

	public function search()
	{
		$this->db	->select('n.news_id', 'n.date', 'nl.title', 'nl.introduction', 'nl.content', 'u.user_id', 'u.username', 'c.category_id', 'cl.title as category')
					->from('nf_news n')
					->join('nf_news_lang nl',            'n.news_id     = nl.news_id')
					->join('nf_news_categories c',       'n.category_id = c.category_id')
					->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
					->join('nf_users u',                 'n.user_id     = u.user_id AND u.deleted = "0"')
					->where('nl.lang', $this->config->lang)
					->where('cl.lang', $this->config->lang)
					->where('n.published', TRUE)
					->order_by('n.date DESC');

		return ['nl.title', 'nl.introduction', 'nl.content'];
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/news/controllers/search.php
*/