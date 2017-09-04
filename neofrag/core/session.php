<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Session extends Core
{
	private $_ip_address;
	private $_host_name;
	private $_session_id;
	private $_user_data = [];
	private $_user_id   = NULL;
	private $_sessions;

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
						->update('nf_sessions', [
							'session_id'    => $this->_session_id,
							'ip_address'    => $this->_ip_address,
							'host_name'     => $this->_host_name,
							'last_activity' => now()
						]);
		}
		else if (!is_asset() && !$this->url->ajax && !$this->url->ajax_header && $_SERVER['REQUEST_METHOD'] != 'OPTIONS')
		{
			$this->_session_id();

			$crawler = is_crawler();

			if ($crawler !== FALSE)
			{
				$this->db->insert('nf_crawlers', [
					'name' => $crawler,
					'path' => $this->url->request
				]);
			}

			$this->db->insert('nf_sessions', [
				'session_id' => $this->_session_id,
				'ip_address' => $this->_ip_address,
				'host_name'  => $this->_host_name,
				'is_crawler' => $crawler !== FALSE,
				'user_data'  => ''
			]);

			$this->_user_data['session']['date']          = time();
			$this->_user_data['session']['javascript']    = FALSE;
			$this->_user_data['session']['authenticator'] = '';
			$this->_user_data['session']['referer']       = isset($_SERVER['HTTP_REFERER'])    ? $_SERVER['HTTP_REFERER']    : '';
			$this->_user_data['session']['user_agent']    = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		}

		statistics('nf_sessions_max_simultaneous', $this->_sessions = $this->db->select('COUNT(DISTINCT IFNULL(user_id, session_id))')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row(), function($a, $b){ return $a > $b; });
	}

	public function __destruct()
	{
		if (!is_asset() && !$this->url->ajax && !$this->url->ajax_header && $_SERVER['REQUEST_METHOD'] != 'OPTIONS')
		{
			if (in_array($this->_get_url(), ['index', 'admin']) || empty($_SERVER['HTTP_REFERER']))
			{
				$this->_user_data['session']['history'] = [];
			}

			if (!empty($this->_user_data['session']['history']) && end($this->_user_data['session']['history']) != $this->_get_url() && prev($this->_user_data['session']['history']) == $this->_get_url())
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

		if ($count == 1 && in_array($args[0], ['session_id', 'user_id', 'ip_address', 'host_name']))
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
		setcookie($this->config->nf_cookie_name, $this->_session_id, strtotime('+1 year'), url(), '', !empty($_SERVER['HTTPS']), TRUE);
	}

	public function save()
	{
		$this->db	->where('session_id', $this->_session_id)
					->update('nf_sessions', [
						'user_data' => !empty($this->_user_data) ? serialize($this->_user_data) : '',
						'user_id'   => $this->_user_id
					]);

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

		if ($user_id !== NULL)
		{
			$this->db->insert('nf_sessions_history', [
				'session_id'    => $this->_session_id,
				'user_id'       => $user_id,
				'ip_address'    => $this->_ip_address,
				'host_name'     => $this->_host_name,
				'authenticator' => $this->_user_data['session']['authenticator'],
				'referer'       => $this->_user_data['session']['referer'],
				'user_agent'    => $this->_user_data['session']['user_agent']
			]);
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
					->update('nf_sessions', [
						'remember_me' => $remember_me
					]);

		return $this;
	}

	public function disconnect($session_id)
	{
		$this->db	->where('session_id', $session_id)
					->update('nf_sessions', [
						'user_id'     => NULL,
						'remember_me' => FALSE
					]);

		return $this;
	}

	private function _get_url()
	{
		static $url;

		if ($url === NULL)
		{
			$url = $this->url->request;

			if (preg_match('#('.($patern = implode('|', [self::$route_patterns['pages'], self::$route_patterns['page']])).')#', $url, $match) && $match[1])
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

			if ($session['user_id'] !== NULL)
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

	public function debugbar()
	{
		return $this->debug->table($this->_user_data);
	}
}
