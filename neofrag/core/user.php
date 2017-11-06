<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class User extends Core
{
	private $_user_data = [];

	public function __construct()
	{
		parent::__construct();

		if ($this->config->nf_http_authentication && $this->session('user_id') === NULL && $this->session('session', 'http_authentication'))
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
									->where('username', $login, 'OR', 'email', $login)
									->row();

				if ($user)
				{
					if (!$user['salt'] && $this->password->is_valid($password, $user['password'], FALSE))
					{
						$this->db	->where('user_id', (int)$user['user_id'])
									->update('nf_users', [
										'password' => $user['password'] = $this->password->encrypt($password.($salt = unique_id())),
										'salt'     => $user['salt'] = $salt
									]);
					}

					if ($this->password->is_valid($password.$user['salt'], $user['password']))
					{
						$this->login((int)$user['user_id'], FALSE);

						if ($this->url->request == 'user/logout')
						{
							redirect();
						}
					}
				}
			}
		}

		setlocale(LC_ALL, $this->lang('locale'));

		$this->_init();
	}

	public function __invoke($var = NULL)
	{
		if ($var === NULL)
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
			$user = $this->db	->select('u.username', 'u.password', 'u.salt', 'u.email', 'u.admin', 'u.language', 'u.registration_date', 'p.first_name', 'p.last_name', 'p.avatar', 'p.signature', 'p.date_of_birth', 'p.sex', 'p.location', 'p.website', 'p.quote')
								->from('nf_users u')
								->join('nf_users_profiles p', 'u.user_id = p.user_id')
								->where('u.user_id', $user_id)
								->where('u.deleted', FALSE)
								->row();
			if ($user)
			{
				$this->_user_data['user_id']            = (int)$user_id;
				$this->_user_data['username']           = $user['username'];
				$this->_user_data['password']           = $user['password'];
				$this->_user_data['salt']               = $user['salt'];
				$this->_user_data['email']              = $user['email'];
				$this->_user_data['admin']              = (bool)$user['admin'];
				$this->_user_data['language']           = $user['language'];
				$this->_user_data['registration_date']  = $user['registration_date'];
				$this->_user_data['last_activity_date'] = now();

				$this->_user_data['first_name']         = $user['first_name'];
				$this->_user_data['last_name']          = $user['last_name'];
				$this->_user_data['avatar']             = $user['avatar'];
				$this->_user_data['signature']          = $user['signature'];
				$this->_user_data['date_of_birth']      = $user['date_of_birth'];
				$this->_user_data['sex']                = $user['sex'];
				$this->_user_data['location']           = $user['location'];
				$this->_user_data['website']            = $user['website'];
				$this->_user_data['quote']              = $user['quote'];

				$this->db	->where('user_id', $this->_user_data['user_id'])
							->update('nf_users', [
								'last_activity_date' => now()
							]);
			}
		}

		$this->check_http_authentification();

		if ($this->config->nf_maintenance)
		{
			if ($this->config->nf_maintenance_opening && date_create() >= ($opening = date_create($this->config->nf_maintenance_opening)))
			{
				$this	->config('nf_maintenance', FALSE, 'bool')
						->config('nf_maintenance_opening', '');
			}
			else if (!$this('admin') && $this->url->request != 'user/logout')
			{
				header('HTTP/1.0 503 Service Unavailable');

				if (!empty($opening))
				{
					header('Retry-After: '.$opening->setTimezone(new DateTimezone('UTC'))->format('D, d M Y H:i:s \G\M\T'));
				}

				if (!$this->url->ajax_header)
				{
					$form_login = $this
						->form
						->set_id('dd74f62896869c798933e29305aa9473')
						->add_rules([
							'login' => [
								'rules' => 'required'
							],
							'password' => [
								'type'  => 'password'
							]
						])
						->save();

					if ($form_login->is_valid($post))
					{
						$user_id = $this->module('user', TRUE)->model()->check_login($post['login'], $hash, $salt);

						if ($user_id > 0 && $this->password->is_valid($post['password'].$salt, $hash, (bool)$salt))
						{
							$this->login($user_id, FALSE);
							refresh();
						}
					}

					$theme = $this->theme('default')->load();

					$this	->css('font.open-sans.300.400.600.700.800')
							->css('jquery.countdown')
							->css('style.maintenance')
							->js('jquery.countdown')
							->js('maintenance');

					echo $theme->view('default', [
						'body'       => $theme->view('maintenance', [
							'page_title' => $page_title = $this->config->nf_maintenance_title ?: NeoFrag()->lang('Site en maintenance')
						]),
						'lang'       => $this->config->lang,
						'css'        => output('css'),
						'js'         => output('js'),
						'js_load'    => output('js_load'),
						'page_title' => $page_title.' :: '.$this->config->nf_name
					]);
				}

				exit;
			}
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

		if (!$this->config->nf_maintenance)
		{
			$this->_init();
		}
	}

	public function logout()
	{
		$this->session	->remember_me(FALSE)
						->set_user_id(NULL)
						->destroy();

		$this->_user_data = [];
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

	public function get_messages()
	{
		static $count;

		if ($count === NULL)
		{
		 $count= $this->db	->select('COUNT(*)')
							->from('nf_users_messages_recipients r')
							->join('nf_users_messages            m',   'r.message_id = m.message_id',    'INNER')
							->join('nf_users_messages_replies    mr',  'mr.reply_id  = m.last_reply_id', 'INNER')
							->join('nf_users_messages_replies    mr2', 'mr2.reply_id  = m.reply_id',     'INNER')
							->where('r.user_id', $this('user_id'))
							->where('(r.date < mr.date OR (r.date IS NULL AND r.user_id <> mr2.user_id))')
							->row();
		}

		return $count;
	}

	public function check_http_authentification()
	{
		if (!$this() && $this->config->nf_http_authentication && $this->session('session', 'http_authentication') !== TRUE)
		{
			$this->session->set('session', 'http_authentication', TRUE);

			header('WWW-Authenticate: Basic realm="'.$this->config->nf_http_authentication_name.'", encoding="UTF-8"');
			header('HTTP/1.0 401 Unauthorized');
			exit;
		}
	}

	public function link($user_id = 0, $username = '', $prefix = '')
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

		return '<a class="user-profile" data-user-id="'.$user_id.'" data-username="'.url_title($username).'" href="'.url('user/'.$user_id.'/'.url_title($username)).'">'.$prefix.$username.'</a>';
	}

	public function avatar($avatar = 0, $sex = '', $user_id = NULL, $username = '')
	{
		if ($this->_user_data && !func_num_args())
		{
			$avatar   = $this('avatar');
			$sex      = $this('sex');
			$user_id  = $this('user_id');
			$username = $this('username');
		}

		return $this->view('user/avatar', [
			'user_id'  => $user_id,
			'username' => $username,
			'avatar'   => !empty($avatar) ? path($avatar) : image($sex == 'female' ? 'default_avatar_female.jpg' : 'default_avatar_male.jpg')
		]);
	}
}
