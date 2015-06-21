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

class m_talks_m_talks extends Model
{
	public function get_messages($talks_id, $message_id = 0, $limit = FALSE)
	{
		$this->db	->select('m.*', 'u.username', 'up.avatar', 'up.sex')
					->from('nf_talks_messages m')
					->join('nf_users u', 'u.user_id = m.user_id')
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
	
	public function add_talk($title, $is_private)
	{
		$talk_id = $this->db->insert('nf_talks', array(
			'name' => $title
		));
		
		$this->_talk_permission($talk_id, $is_private); 
	}
	
	public function edit_talk($talk_id, $title, $is_private)
	{
		$this->db	->where('talk_id', $talk_id)
					->update('nf_talks', array(
						'name' => $title
					));
		
		delete_permission('talks', $talk_id);
		$this->_talk_permission($talk_id, $is_private);
	}
	
	public function delete_talk($talk_id)
	{
		$this->db	->where('talk_id', $talk_id)
					->delete('nf_talks');
		
		delete_permission('talks', $talk_id);
	}
	
	private function _talk_permission($talk_id, $is_private)
	{
		$permissions = array('write' => 'members', 'delete' => 'admins');
		
		if ($is_private)
		{
			$permissions = array_merge($permissions, array(
				'read' => ''
			));
		}
		
		foreach ($permissions as $permission => $group)
		{
			add_permission('talks', $talk_id, $permission, array(
				array(
					'entity_id'  => $is_private ? 'admins' : $group,
					'type'       => 'group',
					'authorized' => TRUE
				)
			));
		}
	}
}

/*
NeoFrag Alpha 0.1
./modules/talks/models/talks.php
*/