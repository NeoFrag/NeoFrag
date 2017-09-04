<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Search extends Controller_Module
{
	public function index($result, $keywords)
	{
		$result['introduction'] = highlight($result['introduction']."\r\r".$result['content'], $keywords);
		return $this->view('search/index', $result);
	}

	public function detail($result, $keywords)
	{
		$result['introduction'] = highlight($result['introduction']."\r\r".$result['content'], $keywords, 1024);
		return $this->view('search/index', $result);
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
