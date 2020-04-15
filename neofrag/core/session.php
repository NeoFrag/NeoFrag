<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Session extends Core
{
	protected $_session;
	protected $_data;

	public function __construct($config = [])
	{
		/*
			TODO 0.2
			 - history_back
			 - session_history => user logged
			 - asset / ajax
		*/

		if ($this->url->cli || is_crawler() || (isset($config['avoid']) && is_a($config['avoid'], 'closure') && $config['avoid']()))
		{
			$this->_session = $this->model2('session');
			$this->_data    = $this->array;
		}
		else
		{
			if ($this->config->nf_cookie_expire)
			{
				$expiration_date = $this->date()->sub($this->config->nf_cookie_expire);

				$this->db	->where('remember', FALSE)
							->where('last_activity <', $expiration_date->sql())
							->delete('nf_session');
			}

			$cookie_name = $this->config->nf_cookie_name;

			if ($this->url->https)
			{
				$cookie_name .= '_https';
			}

			$this->_session = $this->model2('session', isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : NULL);

			$this->_data = $this->_session->data->__extends($this);

			$set_cookie = function() use ($cookie_name){
				do
				{
					$this->_session->set('id', unique_id());
				}
				while (!$this->_session->commit());

				setcookie($cookie_name, $this->_session->id, strtotime('+1 year'), $this->url->base, $this->url->domain, $this->url->https, TRUE);
			};

			if ($this->_session())
			{
				//TODO 0.2 check levenshtein() on ip / user_agent?

				if (isset($expiration_date) && $this->_session->last_activity->timestamp() < $expiration_date->timestamp())
				{
					$set_cookie();
				}

				$this->_session->set('last_activity', NeoFrag()->date())->update();
			}
			else
			{
				$set_cookie();

				$this->set('session', [
					'date'       => NeoFrag()->date(),
					'ip_address' => $ip_address = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP']                     : $_SERVER['REMOTE_ADDR'],
					'host_name'  => utf8_string(gethostbyaddr($ip_address)),
					'referer'    => isset($_SERVER['HTTP_REFERER'])                 ? utf8_htmlentities($_SERVER['HTTP_REFERER'])    : '',
					'user_agent' => isset($_SERVER['HTTP_USER_AGENT'])              ? utf8_htmlentities($_SERVER['HTTP_USER_AGENT']) : ''
				]);
			}

			statistics('nf_sessions_max_simultaneous', $this->db->select('COUNT(DISTINCT IFNULL(user_id, id))')->from('nf_session')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row(), function($a, $b){ return $a > $b; });

			$this->on('output_loaded', function(){
				$this->_session->set('data', $this->_data)->update();
			});
		}

		NeoFrag()->user = $user = $this->_session->user;

		if ($user())
		{
			$user->set('last_activity_date', NeoFrag()->date())->update();
		}

		$this	->trigger('session_init', $this)
				->debug->bar('session', function(){
					return $this->_data->__toArray();
				});
	}

	public function __invoke()
	{
		return call_user_func_array([$this->_data, 'get'], func_get_args());
	}

	public function __get($name)
	{
		if (isset($this->_session->$name))
		{
			return $this->_session->$name;
		}

		return parent::__get($name);
	}

	public function __call($name, $args)
	{
		if ($this->_data && !isset($this->$name))
		{
			return call_user_func_array([$this->_data, $name], $args);
		}
		else
		{
			return parent::__call($name, $args);
		}
	}

	public function login($user, $remember = NULL)
	{
		$this->_session	->set('user', $user)
						->set_if($remember !== NULL, 'remember', $remember)
						->update();

		$this	->model2('session_history')
				->set('user',       $user)
				->set('ip_address', $ip_address = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'])
				->set('host_name',  utf8_string(gethostbyaddr($ip_address)))
				->set('referer',    (string)$this('session', 'referer'))
				->set('user_agent', isset($_SERVER['HTTP_USER_AGENT']) ? utf8_htmlentities($_SERVER['HTTP_USER_AGENT']) : '')
				->set('auth',       $this('session', 'auth'))
				->create();

		return $this;
	}

	public function logout()
	{
		$this->_session->set('user', NULL)->update();
		return $this;
	}

	public function current_sessions()
	{
		return NeoFrag()->collection('session')
						->where('_.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)');
	}
}
