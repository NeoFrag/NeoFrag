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

class m_forum_c_search extends Controller
{
	public function index($result, $keywords)
	{
		$result['message'] = highlight($result['message'], $keywords);
		return $this->load->view('search/index', $result);
	}

	public function detail($result, $keywords)
	{
		$result['message'] = highlight($result['message'], $keywords, 1024);
		return $this->load->view('search/index', $result);
	}

	public function search()
	{
		$this->db	->select('t.topic_id', 't.title as topic_title', 'm.message_id', 'm.message', 'm.date', 'u.user_id', 'u.username', 't.forum_id', 'f.title', 't.count_messages')
					->from('nf_forum_messages m')
					->join('nf_forum_topics   t',  'm.topic_id = t.topic_id')
					->join('nf_forum          f',  't.forum_id = f.forum_id')
					->join('nf_users          u',  'm.user_id = u.user_id AND u.deleted = "0"')
					->order_by('m.date DESC');

		return array('t.title', 'm.message');
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/forum/controllers/search.php
*/