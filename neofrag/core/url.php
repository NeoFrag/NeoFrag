<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Url extends Core
{
	protected $_const      = [];
	protected $_external   = FALSE;
	protected $_production = FALSE;

	public function __construct($config = [])
	{
		if (preg_match('_/{2,}_', $_SERVER['REQUEST_URI']))
		{
			header('Location: '.preg_replace('_/+_', '/', $_SERVER['REQUEST_URI']));
			exit;
		}

		if (array_key_exists('production', $config))
		{
			$this->_production = $config['production'];
		}

		$this->_const['query']        = !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
		$this->_const['https']        = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off';
		$this->_const['location']     = ($this->https ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$url = parse_url($this->location);

		$this->_const['host']         = $url['host'];
		$this->_const['domain']       = isset($config['domain']) && is_a($config['domain'], 'closure') ? call_user_func_array($config['domain'], [$this->_const]) : '';
		$this->_const['subdomain']    = $this->domain && $this->host != $this->domain ? substr($this->host, 0, -strlen($this->domain) - 1) : '';
		$this->_const['base']         = substr($_SERVER['SCRIPT_NAME'], 0, -9);//-strlen('index.php')
		$this->_const['ajax_header']  = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

		if (substr($request = substr($url['path'], strlen($this->base)), -1) == '/')
		{
			header('Location: '.$this->base.substr($request, 0, -1));
			exit;
		}

		$segments = function($request) use ($config){
			$this->_const['request']           = $request;
			$this->_const['extension']         = extension($this->request);
			$this->_const['segments']          = explode('/', $this->extension ? substr($this->request, 0, - strlen($this->extension) - 1) : $this->request ?: 'index');

			if (preg_match('/^(humans|robots)\.txt$/', $this->request, $match))
			{
				$this->_const['segments']      = explode('/', 'ajax/settings/'.$match[1]);
			}

			if (isset($config['segments']) && is_a($config['segments'], 'closure'))
			{
				$this->_const['segments']      = call_user_func_array($config['segments'], [$this->_const]);
			}

			$this->_const['admin']             = $this->segments[0] == 'admin';
			$this->_const['ajax']              = isset($this->segments[(int)$this->admin]) && $this->segments[(int)$this->admin] == 'ajax';
		};

		$segments($request);

		$this->on('config_init', function(){
			if (is_asset())
			{
				asset($this->request);
			}

			if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
			{
				$this->debug('URL', 'LOCATION', $this->location);
				$this->debug('URL', 'SEGMENTS', implode(' / ', $this->segments));
			}
		});

		$this->on('config_langs_listed', function($langs, &$lang) use (&$segments){
			if (array_key_exists($name = $this->segments[0], $langs))
			{
				$lang = $langs[$name];
				$segments(preg_replace('_^'.$name.'/?_', '', $this->request));
			}
			else if (!preg_match('_^user/auth/_', $this->request))
			{
				$this->on('config_lang_selected', function(){
					redirect($this->request.$this->query);
				});
			}
		});

		$this->on('output_loaded', function(){
			return;
			//TODO 0.2
			$url = preg_replace('#'.implode('|', [self::$route_patterns['pages'], self::$route_patterns['page']]).'#', '', $this->url->request);

			if (in_array($url, ['', 'index', 'admin']) || empty($_SERVER['HTTP_REFERER']))
			{
				$this->_data['session']['history'] = [];
			}

			if (!empty($this->_data['session']['history']) && end($this->_data['session']['history']) != $url && prev($this->_data['session']['history']) == $url)
			{
				array_pop($this->_data['session']['history']);
			}
			else
			{
				$this->_data['session']['history'][] = $url;
			}
		});

		$this->debug->bar('request', function(){
			return $this->_const;
		});
	}

	public function __set($name, $value)
	{
	}

	public function __get($name)
	{
		if (isset($this->_const[$name]))
		{
			if ($name == 'base' && $this->_external)
			{
				return ($this->https ? 'https' : 'http').'://'.$this->host.$this->_const['base'];
			}

			return $this->_const[$name];
		}

		return parent::__get($name);
	}

	public function __isset($name)
	{
		return isset($this->_const[$name]);
	}

	public function __invoke($url = '')
	{
		$domain = '';

		if ($url == '#')
		{
			return $url;
		}
		else if (substr($url, 0, 2) == '//')
		{
			$url = explode('/', substr($url, 2));

			if (($subdomain = array_shift($url)) != $this->subdomain)
			{
				$domain = '//'.implode('.', array_filter([$subdomain, $this->domain]));
			}

			$url = implode('/', $url);
		}
		else if (is_valid_url($url))
		{
			return $url;
		}

		if (!$domain && $this->subdomain)
		{
			$url = explode('/', $url);

			if (current($url) == $this->config->nf_default_page)
			{
				array_shift($url);
			}

			$url = implode('/', $url);
		}

		if ($this->config->langs)
		{
			$url = rtrim($this->config->lang->info()->name.'/'.$url, '/');
		}

		return str_replace('/#', '#', $domain.$this->base.$url);
	}

	public function ajax()
	{
		return in_array($this->extension, ['json', 'txt', 'xml']) || $this->ajax || $this->output->data->get('module', 'ajax');
	}

	public function external($external)
	{
		$this->_external = $external;
		return $this;
	}

	public function production()
	{
		if (is_a($this->_production, 'closure'))
		{
			$this->_production = call_user_func($this->_production);
		}

		return $this->_production;
	}

	public function back()
	{
		if ($history = $this->session('session', 'history'))
		{
			if (($i = array_search($this->request, $history)) !== FALSE)
			{
				$history = array_slice($history, 0, $i);
			}

			$url = array_pop($history) ?: NULL;

			$this->session->set('session', 'history', $history);

			return $url;
		}
	}

	public function redirect($location)
	{
		if ($this->ajax())
		{
			$output = $this->json(['redirect' => $location], FALSE);
		}
		else
		{
			header('Location: '.$location);
			$output = '';
		}

		$this->trigger('output', $output);
	}

	public function refresh()
	{
		if ($this->ajax())
		{
			$output = $this->json(['success' => 'refresh'], FALSE);
		}
		else
		{
			header('Location: '.$_SERVER['REQUEST_URI'].$this->query);
			$output = '';
		}

		$this->trigger('output', $output);
	}
}
