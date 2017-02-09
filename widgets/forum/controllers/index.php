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

class w_forum_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		$messages = $this->model()->get_last_messages();
		
		if (!empty($messages))
		{
			return $this->panel()
						->heading($this('last_messages'))
						->body($this->load->view('index', [
							'messages' => $messages
						]))
						->footer('<a href="'.url('forum.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('access_forum').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this('last_messages'))
						->body($this('no_messages'));
		}
	}
	
	public function topics($config = [])
	{
		$topics = $this->model()->get_last_topics();
		
		if (!empty($topics))
		{
			return $this->panel()
						->heading($this('last_topics'))
						->body($this->load->view('topics', [
							'topics' => $topics
						]))
						->footer('<a href="'.url('forum.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('access_forum').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this('last_topics'))
						->body($this('no_topics'));
		}
	}
	
	public function statistics($config = [])
	{
		return $this->panel()
					->heading($this('statistics'), 'fa-signal')
					->body($this->load->view('statistics', [
						'topics'    => $topics = $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->row(),
						'messages'  => $this->db->select('COUNT(message_id)')->from('nf_forum_messages')->row() - $topics,
						'announces' => $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->where('status', ['-2', '1'])->row(),
						'users'     => $this->db->select('COUNT(DISTINCT user_id)')->from('nf_forum_messages')->row()
					]), FALSE);
	}
	
	public function activity($config = [])
	{
		$users = $this->db->select('DISTINCT u.user_id', 'u.username')->from('nf_sessions s')->join('nf_users u', 'u.user_id = s.user_id AND u.deleted = "0"', 'INNER')->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('s.is_crawler', FALSE)->get();

		array_natsort($users, function($a){
			return $a['username'];
		});

		return $this->panel()
					->heading($this('forum_activity'), 'fa-globe')
					->body($this->load->view('activity', [
						'users'    => $users,
						'visitors' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('user_id', NULL)->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row()
					]));
	}
}

/*
NeoFrag Alpha 0.1.5
./widgets/forum/controllers/index.php
*/