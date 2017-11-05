<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_m_user extends Model
{
	/* -1 -> Compte qui n'a pas été activé par mail
	 * 0  -> Compte inconnu
	 * n  -> Identifiant du membre
	 */
	public function check_login($login, &$password, &$salt)
	{
		$user = $this->db	->select('user_id', 'password', 'salt', 'last_activity_date')
							->from('nf_users')
							->where('deleted', FALSE)
							->where('username', $login, 'OR', 'email', $login)
							->row();

		if ($user)
		{
			if ($user['last_activity_date'] == NULL)
			{
				return -1;
			}
			
			$password = $user['password'];
			$salt     = $user['salt'];

			return (int)$user['user_id'];
		}
			
		return 0;
	}

	public function check_user($user_id, $title)
	{
		$user = $this->db	->select('user_id', 'username')
							->from('nf_users')
							->where('deleted', FALSE)
							->where('user_id', (int)$user_id)
							->row();

		if ($user && url_title($user['username']) == $title)
		{
			return $user;
		}
	}

	public function edit_user($username, $email, $first_name, $last_name, $avatar, $date_of_birth, $sex, $location, $website, $quote, $signature, $user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = $this->user('user_id');
		}
		
		$this->db	->where('user_id', $user_id)
					->update('nf_users', [
						'username' => $username,
						'email'    => $email
					]);
		
		$data = [
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'avatar'        => $avatar ?: NULL,
			'date_of_birth' => $date_of_birth ?: NULL,
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
		
		return $this;
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

	public function update_password($password)
	{
		$this->db	->where('user_id', $this->user('user_id'))
					->update('nf_users', [
						'password' => $this->password->encrypt($password.($salt = unique_id())),
						'salt'     => $salt
					]);

		return $this;
	}

	public function add_key($user_id)
	{
		$this->db->insert('nf_users_keys', [
			'key_id'     => $key_id = unique_id($this->db->select('key_id')->from('nf_users_keys')->get()),
			'user_id'    => $user_id,
			'session_id' => $this->session('session_id')
		]);

		return $key_id;
	}

	public function delete_key($key_id)
	{
		$this->db	->where('key_id', $key_id)
					->delete('nf_users_keys');

		return $this;
	}

	public function check_key($key_id)
	{
		return $this->db->select('user_id')
						->from('nf_users_keys')
						->where('key_id', $key_id)
						->row();
	}

	public function get_user_profile($user_id)
	{
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'up.first_name', 'up.last_name', 'up.signature', 'up.date_of_birth', 'up.location', 'up.website', 'up.quote', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.user_id', $user_id)
						->where('u.deleted', FALSE)
						->row();
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

	public function get_members()
	{
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.deleted', FALSE)
						->group_by('u.username')
						->order_by('u.username')
						->get();
	}
}
