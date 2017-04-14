<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Search extends Controller_Module
{
	public function index($result, $keywords)
	{
		$result['message'] = highlight($result['message'], $keywords);
		return $this->view('search/index', $result);
	}

	public function detail($result, $keywords)
	{
		$result['message'] = highlight($result['message'], $keywords, 1024);
		return $this->view('search/index', $result);
	}

	public function search()
	{
		$categories = array_filter($this->db->select('category_id')->from('nf_forum_categories')->get(), function($a){
			return $this->access('forum', 'category_read', $a);
		});

		$this->db	->select('t.topic_id', 't.title as topic_title', 'm.message_id', 'm.message', 'm.date', 'u.id as user_id', 'u.username', 't.forum_id', 'f.title', 't.count_messages')
					->from('nf_forum_messages m')
					->join('nf_forum_topics   t',  'm.topic_id = t.topic_id')
					->join('nf_forum          f',  't.forum_id = f.forum_id')
					->join('nf_forum          f2', 'f.parent_id = f2.forum_id AND f.is_subforum = "1"')
					->join('nf_user           u',  'm.user_id = u.id AND u.deleted = "0"')
					->where('IFNULL(f2.parent_id, f.parent_id)', $categories)
					->order_by('m.date DESC');

		return ['t.title', 'm.message'];
	}
}
