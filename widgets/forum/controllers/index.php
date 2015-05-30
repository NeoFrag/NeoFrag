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

class w_forum_c_index extends Controller_Widget
{
	public function index($config = array())
	{
		$messages = $this->model()->get_last_messages();
		
		if (!empty($messages))
		{
			return new Panel(array(
				'title'        => 'Derniers messages',
				'content'      => $this->load->view('index', array(
					'messages' => $messages
				)),
				'footer'       => '<a href="{base_url}forum.html"><i class="fa fa-arrow-circle-o-right"></i> Accèder au forum</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Derniers messages',
				'content' => 'Aucun message pour le moment'
			));
		}
	}
	
	public function topics($config = array())
	{
		$topics = $this->model()->get_last_topics();
		
		if (!empty($topics))
		{
			return new Panel(array(
				'title'        => 'Derniers sujets',
				'content'      => $this->load->view('topics', array(
					'topics' => $topics
				)),
				'footer'       => '<a href="{base_url}forum.html"><i class="fa fa-arrow-circle-o-right"></i> Accèder au forum</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Derniers sujets',
				'content' => 'Aucun sujet pour le moment'
			));
		}
	}
	
	public function statistics($config = array())
	{
		return new Panel(array(
			'title'   => 'Statistiques',
			'icon'    => 'fa-signal',
			'body'    => FALSE,
			'content' => $this->load->view('statistics', array(
				'topics'    => $topics = $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->row(),
				'messages'  => $this->db->select('COUNT(message_id)')->from('nf_forum_messages')->row() - $topics,
				'announces' => $this->db->select('COUNT(topic_id)')->from('nf_forum_topics')->where('status', array(1, -1))->row(),
				'users'     => $this->db->select('COUNT(DISTINCT user_id)')->from('nf_forum_messages')->row()
			))
		));
	}
	
	public function activity($config = array())
	{
		$users = $this->db->select('DISTINCT u.user_id', 'u.username')->from('nf_sessions s')->join('nf_users u', 'u.user_id = s.user_id', 'INNER')->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->get();
		
		usort($users, function($a, $b){
			return strnatcmp(strtolower($a['username']), strtolower($b['username']));
		});
		
		return new Panel(array(
			'title'   => 'Activité du forum',
			'icon'    => 'fa-globe',
			'content' => $this->load->view('activity', array(
				'users'    => $users,
				'visitors' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('user_id', NULL)->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row()
			))
		));
	}
}

/*
NeoFrag Alpha 0.1
./widgets/forum/controllers/index.php
*/