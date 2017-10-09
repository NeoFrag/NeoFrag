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

	public function __construct()
	{
		/*
			TODO 0.1.7
			 - history_back
			 - session_history => user logged
			 - asset / ajax
		*/

		if (is_crawler())
		{
			$this->_session = $this->model2('session');
		}
		else
		{
			$expiration_date = $this->date()->sub($this->config->nf_cookie_expire);

			$this->db	->where('remember', FALSE)
						->where('last_activity <', $expiration_date->sql())
						->delete('nf_session');

			$this->_session = $this->model2('session', isset($_COOKIE[$this->config->nf_cookie_name]) ? $_COOKIE[$this->config->nf_cookie_name] : NULL);

			$this->_data = $this->array($this->_session->data ?: [])->__extends($this);

			$set_cookie = function(){
				do
				{
					$this->_session->set('id', unique_id());
				}
				while (!$this->_session->commit());

				$domain = '';

				//TODO 0.1.7
				if (preg_match('/(neofr\.ag|neofrag\.download|neofrag)$/', $_SERVER['HTTP_HOST'], $match))
				{
					$domain = $match[1];
				}

				setcookie($this->config->nf_cookie_name, $this->_session->id, strtotime('+1 year'), $this->url->base, $domain, !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off', TRUE);
			};

			if ($this->_session())
			{
				//TODO 0.1.7 check levenshtein() on ip / user_agent?

				if ($this->_session->last_activity->timestamp() < $expiration_date->timestamp())
				{
					$set_cookie();
				}

				$this->_session->set('last_activity', $this->date());
			}
			else
			{
				$set_cookie();

				$this->set('session', [
					'date'       => $this->date(),
					'ip_address' => isset($_SERVER['HTTP_X_REAL_IP'])  ? $_SERVER['HTTP_X_REAL_IP']                     : $_SERVER['REMOTE_ADDR'],
					'referer'    => isset($_SERVER['HTTP_REFERER'])    ? utf8_htmlentities($_SERVER['HTTP_REFERER'])    : '',
					'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? utf8_htmlentities($_SERVER['HTTP_USER_AGENT']) : ''
				]);
			}

			statistics('nf_sessions_max_simultaneous', $this->db->select('COUNT(DISTINCT IFNULL(user_id, id))')->from('nf_session')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row(), function($a, $b){ return $a > $b; });

			$this->on('output_loaded', function(){
				$this->_session->set('data', $this->_data->__toArray())->update();
			});
		}

		\NeoFrag()->user = $this->_session->user;

		$this	->trigger('session_init')
				->debug->bar('session', function(){
					return $this->_data->__toArray();
				});
	}

	public function __invoke()
	{
		return call_user_func_array([$this->_data, 'get'], func_get_args());
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

		//TODO Session_history

		$this->url->refresh();

		return $this;
	}

	public function logout()
	{
		$this->_session->set('user', NULL)->update();
		return $this;
	}
}
