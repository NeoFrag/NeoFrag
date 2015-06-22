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

class Session extends Core
{
	private $_ip_address;
	private $_host_name;
	private $_session_id;
	private $_user_data = array();
	private $_user_id   = NULL;
	private	$_sessions;

	public function __construct()
	{
		parent::__construct();

		$this->db	->where('remember_me', FALSE)
					->where('UNIX_TIMESTAMP(last_activity) <', time() - strtoseconds($this->config->nf_cookie_expire))
					->delete('nf_sessions');

		$this->_ip_address = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$this->_host_name  = utf8_string(gethostbyaddr($this->_ip_address));

		if (isset($_COOKIE[$this->config->nf_cookie_name]) && $this->_check_cookie($cookie = $_COOKIE[$this->config->nf_cookie_name], $last_activity))
		{
			if (strtotime($this->config->nf_cookie_expire, $last_activity) < time())
			{
				$this->_session_id();
			}
			else
			{
				$this->_session_id = $cookie;
			}

			$this->db	->where('session_id', $cookie)
						->update('nf_sessions', array(
							'session_id'    => $this->_session_id,
							'ip_address'    => $this->_ip_address,
							'host_name'     => $this->_host_name,
							'last_activity' => now()
						));

			if (!is_null($time_zone = $this('session', 'time_zone')))
			{
				set_time_zone($time_zone);
				$this->db->update_time_zone();
			}
		}
		else if (!$this->assets->is_asset() && !$this->config->ajax_url && !$this->config->ajax_header && $_SERVER['REQUEST_METHOD'] != 'OPTIONS')
		{
			$this->_session_id();

			$this->db->insert('nf_sessions', array(
				'session_id' => $this->_session_id,
				'ip_address' => $this->_ip_address,
				'host_name'  => $this->_host_name
			));
			
			$this->_user_data['session']['date']       = time();
			$this->_user_data['session']['javascript'] = FALSE;
			$this->_user_data['session']['referer']    = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$this->_user_data['session']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}

		statistics('nf_sessions_max_simultaneous', $this->_sessions = $this->db->select('COUNT(DISTINCT IFNULL(user_id, session_id))')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row(), function($a, $b){ return $a > $b; });
	}
	
	public function __destruct()
	{
		if ($this->assets->is_asset() || $this->config->ajax_url || $this->config->ajax_header || $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
		{
			return;
		}
		
		if (in_array($this->_get_url(), array('index.html', 'admin.html')) || empty($_SERVER['HTTP_REFERER']))
		{
			$this->_user_data['session']['history'] = array($this->_get_url());
		}
		else if (empty($this->_user_data['session']['history']) || end($this->_user_data['session']['history']) != $this->_get_url())
		{
			if (prev($this->_user_data['session']['history']) == $this->_get_url())
			{
				array_pop($this->_user_data['session']['history']);
			}
			else
			{
				$this->_user_data['session']['history'][] = $this->_get_url();
			}
		}
		
		$this->save();
	}

	public function __invoke()
	{
		$args  = func_get_args();
		$count = func_num_args();

		if ($count == 1 && in_array($args[0], array('session_id', 'user_id', 'ip_address', 'host_name')))
		{
			$var = '_'.$args[0];
			return $this->$var;
		}

		$var = '$this->_user_data';
		for ($i = 0; $i < $count; $i++)
		{
			$var .= '[$args['.$i.']]';
		}

		return eval('return (isset('.$var.')) ? '.$var.' : NULL;');
	}

	private function _session_id()
	{
		$this->_session_id = unique_id($this->db->select('session_id')->from('nf_sessions')->get());
		setcookie($this->config->nf_cookie_name, $this->_session_id, strtotime('+1 year'), $this->config->base_url);
	}

	public function save()
	{
		$this->db	->where('session_id', $this->_session_id)
					->update('nf_sessions', array(
						'user_data' => !empty($this->_user_data) ? serialize($this->_user_data) : '',
						'user_id'   => $this->_user_id
					));

		return $this;
	}

	public function set()
	{
		$args  = func_get_args();
		$count = func_num_args();

		$var = '$this->_user_data';
		for ($i = 0; $i < $count - 1; $i++)
		{
			$var .= '[$args['.$i.']]';
		}

		eval($var.' = $args[$count - 1];');

		return $this;
	}

	public function add()
	{
		$args  = func_get_args();
		$count = func_num_args();

		$var = '$this->_user_data';
		for ($i = 0; $i < $count - 1; $i++)
		{
			$var .= '[$args['.$i.']]';
		}

		eval($var.'[] = $args[$count - 1];');

		return $this;
	}

	public function destroy()
	{
		$args  = func_get_args();
		$count = func_num_args();
		
		if ($count == 0)
		{
			foreach (array_keys($this->_user_data) as $key)
			{
				if ($key == 'session')
				{
					continue;
				}
				
				unset($this->_user_data[$key]);
			}
		}
		else
		{
			$var = '$this->_user_data';
			for ($i = 0; $i < $count; $i++)
			{
				$var .= '[$args['.$i.']]';
			}
	
			eval('unset('.$var.');');
		}

		return $this;
	}

	public function set_user_id($user_id)
	{
		$this->_user_id = $user_id;
		
		if (!is_null($user_id))
		{
			$this->db->insert('nf_sessions_history', array(
				'session_id' => $this->_session_id,
				'user_id'    => $user_id,
				'ip_address' => $this->_ip_address,
				'host_name'  => $this->_host_name,
				'referer'    => $this->_user_data['session']['referer'],
				'user_agent' => $this->_user_data['session']['user_agent'],
				'date'       => now()
			));
		}

		return $this;
	}
	
	public function get_session_id()
	{
		return $this->_session_id;
	}
	
	public function get_back()
	{
		if (!empty($this->_user_data['session']['history']))
		{
			$url = $this->_get_url();
			
			if (in_array($url, $history = $this->_user_data['session']['history']))
			{
				$history = array_slice($history, 0, array_search($url, $history));
			}
			
			return array_pop($history) ?: NULL;
		}
	}

	public function remember_me($remember_me)
	{
		$this->db	->where('session_id', $this->_session_id)
					->update('nf_sessions', array(
						'remember_me' => $remember_me
					));

		return $this;
	}

	public function disconnect($session_id)
	{
		$this->db	->where('session_id', $session_id)
					->update('nf_sessions', array(
						'user_id'     => NULL,
						'remember_me' => FALSE
					));

		return $this;
	}
	
	private function _get_url()
	{
		static $url;
		
		if (is_null($url))
		{
			$url = $this->config->request_url;
			
			if (preg_match('#('.($patern = implode('|', array(Module::$patterns['pages'], Module::$patterns['page']))).')\.html$#', $url, $match) && $match[1])
			{
				$url = preg_replace('#'.$patern.'#', '', $url);
			}
		}
		
		return $url;
	}

	private function _check_cookie($cookie_data, &$last_activity)
	{
		$session = $this->db	->select('host_name', 'ip_address', 'UNIX_TIMESTAMP(last_activity) as last_activity', 'user_data', 'user_id')
								->from('nf_sessions')
								->where('session_id', $cookie_data)
								->row();

		if (!empty($session))
		{
			$last_activity = $session['last_activity'];

			if ($session['ip_address'] != $this->_ip_address &&
				levenshtein($session['host_name'], $this->_host_name) > levenshtein($session['ip_address'], $this->_ip_address) + 0.5 * strlen($session['host_name']))
			{
				return FALSE;
			}

			if (!is_null($session['user_id']))
			{
				$this->_user_id = (int)$session['user_id'];
			}

			if ($session['user_data'])
			{
				$this->_user_data = array_merge(unserialize(utf8_html_entity_decode($session['user_data'])), $this->_user_data);
			}

			return TRUE;
		}

		return FALSE;
	}

	public function current_sessions()
	{
		return $this->_sessions;
	}
	
	public function profiler()
	{
		if (!$this->_user_data)
		{
			return '';
		}

		ksort($this->_user_data);

		$output = '	<a href="#" data-profiler="session"><i class="icon-chevron-'.(!empty($this->_user_data['profiler']['session']) ? 'down' : 'up').' pull-right"></i></a>
					<h2>Session</h2>
					<div class="profiler-block">'.$this->profiler->table($this->_user_data).'</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/session.php
*/