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

class User extends Core
{
	private $_user_data = array();

	public function __construct()
	{
		parent::__construct();
		
		if ($this->config->nf_http_authentication && is_null($this->session('user_id')) && $this->session('session', 'http_authentication'))
		{
			$this->session->destroy('session', 'http_authentication');

			if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
			{
				$login    = $_SERVER['PHP_AUTH_USER'];
				$password = $_SERVER['PHP_AUTH_PW'];
			}
			else if (isset($_SERVER['REDIRECT_REMOTE_USER']) && preg_match('/Basic (.*)/', $_SERVER['REDIRECT_REMOTE_USER'], $matches))
			{
				list($login, $password) = explode(':', base64_decode($matches[1]));
			}

			if (isset($login, $password))
			{
				$user = $this->db	->select('user_id', 'password', 'salt')
									->from('nf_users')
									->where('last_activity_date <>', 0)
									->where('deleted', FALSE)
									->where('BINARY username', $login, 'OR', 'BINARY email', $login)
									->row();

				if ($user)
				{
					if (!$user['salt'] && $this->load->library('password')->is_valid($password, $user['password'], FALSE))
					{
						$this->db	->where('user_id', (int)$user['user_id'])
									->update('nf_users', array(
										'password' => $user['password'] = $this->password->encrypt($password.($salt = unique_id())),
										'salt'     => $user['salt'] = $salt,
									));
					}
					
					if ($this->load->library('password')->is_valid($password.$user['salt'], $user['password']))
					{
						$this->login((int)$user['user_id'], FALSE);

						if ($this->config->request_url == 'user/logout.html')
						{
							redirect();
						}
					}
				}
			}
		}

		$this->_init();
	}

	public function __invoke($var = NULL)
	{
		if (is_null($var))
		{
			return !empty($this->_user_data['user_id']);
		}
		else if (isset($this->_user_data[$var]))
		{
			return $this->_user_data[$var];
		}

		return NULL;
	}

	private function _init()
	{
		if ($user_id = $this->session('user_id'))
		{
			$user = $this->db	->select('u.username', 'u.password', 'u.salt', 'u.email', 'u.admin', 'u.theme', 'u.language', 'u.registration_date', 'p.first_name', 'p.last_name', 'p.avatar', 'p.signature', 'p.date_of_birth', 'p.sex', 'p.location', 'p.website', 'p.quote')
								->from('nf_users u')
								->join('nf_users_profiles p', 'u.user_id = p.user_id')
								->where('u.user_id', $user_id)
								->row();
			if ($user)
			{
				$this->_user_data['user_id']              = (int)$user_id;
				$this->_user_data['username']             = $user['username'];
				$this->_user_data['password']             = $user['password'];
				$this->_user_data['salt']                 = $user['salt'];
				$this->_user_data['email']                = $user['email'];
				$this->_user_data['admin']                = (bool)$user['admin'];
				$this->_user_data['theme']                = $user['theme'];
				$this->_user_data['language']             = $user['language'];
				$this->_user_data['registration_date']    = $user['registration_date'];
				$this->_user_data['last_activity_date']   = now();
				
				$this->_user_data['first_name']           = $user['first_name'];
				$this->_user_data['last_name']            = $user['last_name'];
				$this->_user_data['avatar']               = $user['avatar'];
				$this->_user_data['signature']            = $user['signature'];
				$this->_user_data['date_of_birth']        = $user['date_of_birth'];
				$this->_user_data['sex']                  = $user['sex'];
				$this->_user_data['location']             = $user['location'];
				$this->_user_data['website']              = $user['website'];
				$this->_user_data['quote']                = $user['quote'];
				
				$this->_user_data['messages_unread'] = $this->db->from('nf_users_messages_recipients')->where('user_id', $this('user_id'))->where('`read`', FALSE)->num_rows();

				$this->db	->where('user_id', $this->_user_data['user_id'])
							->update('nf_users', array(
								'last_activity_date' => now()
							));
			}
		}
	}
	
	public function set($name, $value)
	{
		if (in_array($name, array('language', 'theme')) && $this->_user_data[$name] !== $value)
		{
			$this->db	->where('user_id', $this->_user_data['user_id'])
						->update('nf_users', array(
							$name => $value
						));
		}
	}

	public function login($user_id, $remember_me = FALSE)
	{
		$this->session->set_user_id($user_id);

		if ($remember_me)
		{
			$this->session->remember_me(TRUE);
		}

		$this->session->save();

		$this->_init();
	}

	public function logout()
	{
		$this->session	->remember_me(FALSE)
						->set_user_id(NULL)
						->destroy();

		$this->_user_data = array();
	}

	public function group($group)
	{
		if ($group == 'administrators')
		{
			return $this('admin');
		}

		//TODO
		/*return !is_null(($this->db	->query('	SELECT *
												FROM nf_users_groups u
												JOIN nf_groups       g ON u.group_id = g.group_id
												WHERE u.user_id = %d AND g.name = %s', $this('user_id'), $group)
									->get()));*/
	}

	public function is_allowed($module_name)
	{
		return is_authorized($module_name, 'module_access');
	}

	public function get_online_users()
	{
		$users = array();

		if ($this())
		{
			$users[] = $this('user_id');
		}

		$users = array_merge($users, $this->db	->select('user_id')
												->from('nf_sessions')
												->where('UNIX_TIMESTAMP(last_activity) >=', time() - strtoseconds('5 minutes'))
												->get());

		return array_unique($users);
	}

	public function get_sessions($user_id = NULL)
	{
		$sessions =  $this->db	->from('nf_sessions')
								->where('user_id', $user_id ?: $this('user_id'))
								->order_by('last_activity DESC')
								->get();
		
		//On ajoute des infos de session (time_zone ....)
		foreach ($sessions as &$session)
		{
			$user_data = unserialize(utf8_html_entity_decode($session['user_data']));
			
			unset($session['user_data']);
			
			if (isset($user_data['session']))
			{
				foreach ($user_data['session'] as $key => $value)
				{
					$session[$key] = $value;
				}
			}
		}
		
		return $sessions;
	}

	public function get_sessions_history()
	{
		return $this->db->from('nf_sessions_history')
						->where('user_id', $this('user_id'))
						->order_by('date DESC')
						->get();
	}

	public function check_http_authentification()
	{
		if (!$this() && $this->config->nf_http_authentication && $this->session('session', 'http_authentication') !== TRUE)
		{
			$this->session->set('session', 'http_authentication', TRUE);

			header('WWW-Authenticate: Basic realm="'.utf8_decode($this->config->nf_http_authentication_name).'"');
			header('HTTP/1.0 401 Unauthorized');
			exit;
		}
	}

	public function link($user_id = 0, $username = '')
	{
		if (!$user_id)
		{
			$user_id  = $this('user_id');
			$username = $this('username');
		}
		
		if (!$username)
		{
			$username = $this->db->select('username')->from('nf_users')->where('user_id', $user_id)->row();
		}

		//TODO Ajouter une popover avec un chargement ajax
		return '<a href="{base_url}members/'.$user_id.'/'.url_title($username).'.html">'.$username.'</a>';
	}
	
	public function avatar($avatar = 0, $sex = '')
	{
		if ($this->_user_data && $avatar === 0)
		{
			$avatar = $this('avatar');
			$sex    = $this('sex');
		}
		
		return !empty($avatar) ? $this->assets->file($avatar) : '{image '.($sex == 'female' ? 'default_avatar_female.jpg' : 'default_avatar_male.jpg').'}';
	}

	public function profiler()
	{
		if (!$this->_user_data)
		{
			return '';
		}
		
		$data = array();

		foreach ($this->_user_data as $key => $value)
		{
			if (!in_array($key, array('password', 'salt')))
			{
				$data[$key] = $value;
			}
		}
		
		ksort($data);

		$output = '	<a href="#" data-profiler="user"><i class="icon-chevron-'.(!empty($data['profiler']['user']) ? 'down' : 'up').' pull-right"></i></a>
					<h2>User</h2>
					<div class="profiler-block">'.$this->profiler->table($data).'</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/user.php
*/