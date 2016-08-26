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

class m_talks_m_talks extends Model
{
	public function get_messages($talks_id, $message_id = 0, $limit = FALSE)
	{
		$this->db	->select('m.message_id', 'm.talk_id', 'u.user_id', 'm.message', 'm.date', 'u.username', 'up.avatar', 'up.sex')
					->from('nf_talks_messages m')
					->join('nf_users u', 'u.user_id = m.user_id AND u.deleted = "0"')
					->join('nf_users_profiles up', 'u.user_id = up.user_id');
					
		if ($message_id && !$limit)
		{
			$this->db->where('message_id >=', $message_id);
		}
		else if ($message_id && $limit)
		{
			$this->db	->where('message_id <', $message_id)
						->limit(10);
		}
		else
		{
			$this->db->limit(10);
		}
		
		return $this->db	->where('talk_id', $talks_id)
							->order_by('m.message_id DESC')
							->get();
	}
	
	public function get_talks()
	{
		return $this->db->select('talk_id', 'name')
						->from('nf_talks')
						->order_by('name')
						->get();
	}
	
	public function check_talk($talk_id, $title)
	{
		$talk = $this->db	->select('talk_id', 'name')
							->from('nf_talks')
							->where('talk_id', $talk_id)
							->row();
		
		if ($talk && $title == url_title($talk['name']))
		{
			return $talk;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function add_talk($title)
	{
		$talk_id = $this->db->insert('nf_talks', [
			'name' => $title
		]);
		
		$this->access->init('talks', 'talks', $talk_id);
	}
	
	public function edit_talk($talk_id, $title)
	{
		$this->db	->where('talk_id', $talk_id)
					->update('nf_talks', [
						'name' => $title
					]);
	}
	
	public function delete_talk($talk_id)
	{
		$this->db	->where('talk_id', $talk_id)
					->delete('nf_talks');
		
		$this->access->delete('talks', $talk_id);
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/talks/models/talks.php
*/