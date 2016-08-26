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

class m_news_m_news extends Model
{
	public function get_news($filter = '', $filter_data = '')
	{
		$this->db	->select('n.*', 'nl.title', 'nl.introduction', 'nl.content', 'nl.tags', 'IFNULL(n.image_id, c.image_id) as image', 'c.icon_id as category_icon', 'c.name as category_name', 'cl.title as category_title', 'u.user_id', 'u.username', 'up.avatar', 'up.sex')
					->from('nf_news n')
					->join('nf_news_lang nl',            'n.news_id     = nl.news_id')
					->join('nf_news_categories c',       'n.category_id = c.category_id')
					->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
					->join('nf_users u',                 'n.user_id     = u.user_id AND u.deleted = "0"')
					->join('nf_users_profiles up',       'up.user_id    = u.user_id')
					->where('nl.lang', $this->config->lang)
					->where('cl.lang', $this->config->lang)
					->order_by('n.date DESC');
		
		if (!empty($filter) && !empty($filter_data))
		{
			if ($filter == 'tag')
			{
				$this->db->where('nl.tags FIND_IN_SET', $filter_data);
			}
			else if ($filter == 'category')
			{
				$this->db->where('n.category_id', $filter_data);
			}
		}
		
		if (!$this->config->admin_url)
		{
			$this->db->where('n.published', TRUE);
		}
		
		return $this->db->get();
	}

	public function get_news_by_user($user_id, $news_id)
	{
		return $this->db->select('n.news_id', 'nl.title', 'cl.title as category_title')
						->from('nf_news n')
						->join('nf_news_lang nl',            'n.news_id     = nl.news_id')
						->join('nf_news_categories c',       'n.category_id = c.category_id')
						->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
						->where('n.published', TRUE)
						->where('nl.lang', $this->config->lang)
						->where('cl.lang', $this->config->lang)
						->where('n.user_id', $user_id)
						->where('n.news_id <>', $news_id)
						->order_by('n.date DESC')
						->limit(5)
						->get();
	}

	public function check_news($news_id, $title, $lang = 'default')
	{
		if ($lang == 'default')
		{
			$lang = $this->config->lang;
		}

		$this->db	->select('n.news_id', 'n.category_id', 'u.user_id', 'n.image_id', 'n.date', 'n.published', 'n.views', 'n.vote', 'nl.title', 'nl.introduction', 'nl.content', 'nl.tags', 'c.name as category_name', 'cl.title as category_title', 'IFNULL(n.image_id, c.image_id) as image', 'c.icon_id as category_icon', 'u.username', 'u.admin', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online', 'up.quote', 'up.avatar', 'up.sex')
						->from('nf_news n')
						->join('nf_news_lang nl',            'n.news_id     = nl.news_id')
						->join('nf_news_categories c',       'n.category_id = c.category_id')
						->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
						->join('nf_users u',                 'u.user_id     = n.user_id AND u.deleted = "0"')
						->join('nf_users_profiles up',       'u.user_id     = up.user_id')
						->join('nf_sessions       s',        'u.user_id     = s.user_id')
						->where('n.news_id', $news_id)
						->where('nl.lang', $lang)
						->where('cl.lang', $lang);
		
		if (!$this->config->admin_url)
		{
			$this->db->where('n.published', TRUE);
		}
		
		$news = $this->db->row();

		if ($news && url_title($news['title']) == $title)
		{
			return $news;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_news($title, $category_id, $image_id, $introduction, $content, $tags, $published)
	{
		$news_id = $this->db->insert('nf_news', [
								'category_id' => $category_id,
								'user_id'     => $this->user('user_id'),
								'image_id'    => $image_id,
								'published'   => $published
							]);

		$this->db	->insert('nf_news_lang', [
						'news_id'      => $news_id,
						'lang'         => $this->config->lang,
						'title'        => $title,
						'introduction' => $introduction,
						'content'      => $content,
						'tags'         => $this->_tags($tags)
					]);
	}

	public function edit_news($news_id, $category_id, $image_id, $published, $title, $introduction, $content, $tags, $lang)
	{
		$this->db	->where('news_id', $news_id)
					->update('nf_news', [
						'category_id' => $category_id,
						'image_id'    => $image_id,
						'published'   => $published
					]);

		$this->db	->where('news_id', $news_id)
					->where('lang', $lang)
					->update('nf_news_lang', [
						'title'        => $title,
						'introduction' => $introduction,
						'content'      => $content,
						'tags'         => $this->_tags($tags)
					]);
	}

	public function delete_news($news_id)
	{
		$this	->file		->delete($this->db->select('image_id')->from('nf_news')->where('news_id', $news_id)->row())
				->comments	->delete('news', $news_id);

		$this->db	->where('news_id', $news_id)
					->delete('nf_news');
	}
	
	private function _tags($tags)
	{
		return implode(',', array_unique(array_map('trim', preg_split('/[ ,;]+/', $tags, -1, PREG_SPLIT_NO_EMPTY))));
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/news/models/news.php
*/