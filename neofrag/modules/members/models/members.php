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

class m_members_m_members extends Model
{
	public function check_member($user_id, &$username)
	{
		$user = $this->db	->select('u.user_id', 'u.username', 'u.email', 'up.first_name', 'up.last_name', 'up.signature', 'up.date_of_birth', 'up.sex', 'up.location', 'up.website', 'up.quote', 'up.avatar')
							->from('nf_users u')
							->join('nf_users_profiles up', 'u.user_id = up.user_id')
							->where('u.user_id', $user_id)
							->where('u.deleted', FALSE)
							->row();

		if ($user && url_title($user['username']) == $username)
		{
			$username = $user['username'];
			
			return [
				$user_id,
				$user['username'],
				$user['email'],
				$this->groups($user['user_id']),
				$user['first_name'],
				$user['last_name'],
				$user['avatar'],
				$user['signature'],
				$user['date_of_birth'],
				$user['sex'],
				$user['location'],
				$user['website'],
				$user['quote']
			];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function check_session($session_id)
	{
		return $this->db	->select('s.session_id', 'u.username')
							->from('nf_sessions s')
							->join('nf_users u', 'u.user_id = s.user_id')
							->where('s.session_id', $session_id)
							->where('u.deleted', FALSE)
							->row();
	}
	
	public function edit_member($user_id, $username, $email, $first_name, $last_name, $avatar, $date_of_birth, $sex, $location, $website, $quote, $signature)
	{
		$this->db	->where('user_id', $user_id)
					->update('nf_users', [
						'username' => $username,
						'email'    => $email
					]);
		
		$data = [
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'avatar'        => $avatar,
			'date_of_birth' => $date_of_birth,
			'sex'           => $sex,
			'location'      => $location,
			'website'       => $website,
			'quote'         => $quote,
			'signature'     => $signature
		];
		
		if ($this->db->select('1')->from('nf_users_profiles')->where('user_id', $user_id)->row())
		{
			$this->db	->where('user_id', $user_id)
						->update('nf_users_profiles', $data);
		}
		else
		{
			$this->db->insert('nf_users_profiles', array_merge($data, [
				'user_id' => $user_id
			]));
		}
	}
	
	public function edit_groups($user_id, $groups)
	{
		$this->db	->where('user_id', $user_id)
					->delete('nf_users_groups');
		
		$this->db	->where('user_id', $user_id)
					->update('nf_users', [
						'admin' => FALSE
					]);
		
		if (in_array('admins', $groups))
		{
			$this->db	->where('user_id', $user_id)
						->update('nf_users', [
							'admin' => TRUE
						]);
		}
		
		foreach ($groups as $group_id)
		{
			if ($this->groups()[$group_id]['auto'])
			{
				continue;
			}
			
			$this->db->insert('nf_users_groups', [
				'user_id'  => $user_id,
				'group_id' => $group_id
			]);
		}
	}
	
	public function get_members($users = NULL)
	{
		if (is_array($users))
		{
			$this->db->where('u.user_id', $users);
		}
		
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.deleted', FALSE)
						->group_by('u.username')
						->order_by('u.username')
						->get();
	}
	
	public function get_sessions()
	{
		return $this->db->select('u.user_id', 'u.username', 's.session_id', 's.ip_address', 's.host_name', 's.last_activity', 's.user_data', 's.remember_me')
						->from('nf_sessions s')
						->join('nf_users u', 'u.user_id = s.user_id AND u.deleted = "0"')
						->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')
						->where('s.is_crawler', FALSE)
						->order_by('s.last_activity DESC')
						->get();
	}
	
	public function get_member_profile($user_id)
	{
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'up.first_name', 'up.last_name', 'up.signature', 'up.date_of_birth', 'up.location', 'up.website', 'up.quote', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.user_id', $user_id)
						->where('u.deleted', FALSE)
						->row();
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/members/models/members.php
*/