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
		$this->_const['request']      = $_SERVER['REQUEST_URI'];
		$this->_const['cli']          = FALSE;

		$url = parse_url($this->location);

		$this->_const['host']         = $url['host'];
		$this->_const['domain']       = isset($config['domain']) && is_a($config['domain'], 'closure') ? call_user_func_array($config['domain'], [$this->_const]) : '';
		$this->_const['subdomain']    = $this->domain && $this->host != $this->domain ? substr($this->host, 0, -strlen($this->domain) - 1) : '';
		$this->_const['ajax_header']  = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$this->_const['base']         = @$_SERVER['REDIRECT_CONTEXT'];
		$base2                        = substr($_SERVER['SCRIPT_NAME'], 0, -9);//-strlen('index.php')

		if (strpos($this->request, $this->base.$base2) === 0)
		{
			$this->_const['base'] .= $base2;
		}
		else
		{
			$this->_const['base'] .= '/';
		}

		if (substr($request = substr($url['path'], strlen($this->base)), -1) == '/')
		{
			header('Location: '.$this->base.substr($request, 0, -1));
			exit;
		}

		$segments = function($request) use ($config){
			$this->_const['request']   = $request;
			$this->_const['extension'] = extension($this->request);

			global $argv;

			if (php_sapi_name() == 'cli' && !empty($argv[1]))
			{
				$this->_const['cli']      = TRUE;
				$this->_const['segments'] = array_merge(explode('/', $argv[1]), array_slice($argv, 2));
				$this->_const['base']     = isset($config['base']) ? $config['base'] : '/';
				chdir(NEOFRAG_CMS);
			}
			else
			{
				$this->_const['segments'] = explode('/', $this->extension ? substr($this->request, 0, - strlen($this->extension) - 1) : $this->request ?: 'index');
			}

			if (preg_match('/^(humans|robots)\.txt$/', $this->request, $match))
			{
				$this->_const['segments'] = explode('/', 'ajax/settings/'.$match[1]);
			}

			if (isset($config['segments']) && is_a($config['segments'], 'closure'))
			{
				$this->_const['segments'] = call_user_func_array($config['segments'], [$this->_const]);
			}

			$this->_const['admin'] = $this->segments[0] == 'admin';
			$this->_const['ajax']  = isset($this->segments[(int)$this->admin]) && $this->segments[(int)$this->admin] == 'ajax';

			if (isset($config['override']) && is_a($config['override'], 'closure'))
			{
				call_user_func_array($config['override'], [&$this->_const]);
			}
		};

		$segments($request);

		$this->on('config_init', function() use ($segments){
			if (is_asset())
			{
				$this->output->asset($this->request);
			}
			else if ($this->config->nf_maintenance)
			{
				if ($this->config->nf_maintenance_opening && ($opening = $this->date($this->config->nf_maintenance_opening)) && $opening->diff() <= 0)
				{
					$this	->config('nf_maintenance', FALSE, 'bool')
							->config('nf_maintenance_opening', '');
				}
				else if (!$this->user->admin && !preg_match('#(ajax/user/(lost-password|login)|user/lost-password/[a-z0-9]+|user/logout)#', $this->url->request))
				{
					header('HTTP/1.0 503 Service Unavailable');

					$this->_const['maintenance'] = TRUE;

					if (!empty($opening))
					{
						header('Retry-After: '.$opening->timezone('UTC')->format('D, d M Y H:i:s \G\M\T'));
					}

					if (!$this->url->ajax_header)
					{
						$segments('settings/maintenance');
					}
				}
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

				if (($request = preg_replace('_^'.$name.'/?_', '', $this->request)) != $this->request)
				{
					$segments($request);
				}
			}
			else if (!defined('NEOFRAG_INSTALL') && !$this->cli && !preg_match('_^user/auth/_', $this->request))
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
		if (array_key_exists($name, $this->_const))
		{
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
		$domain = $args = '';

		if ($url == '#')
		{
			return $url;
		}
		else if (substr($url, 0, 2) == '//')
		{
			if (preg_match('_(.*)([?#].*)$_', $url, $match))
			{
				$url  = $match[1];
				$args = $match[2];
			}

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

		if ($this->_external)
		{
			$domain = ($this->https ? 'https' : 'http').':'.($domain ?: '//'.$this->host);
		}

		$url = str_replace('/#', '#', $domain.$this->base.$url.$args);

		if ($this->_external && is_a($this->_external, 'closure'))
		{
			$url = call_user_func($this->_external, $url);
		}

		return $url;
	}

	public function ajax()
	{
		return $this->cli || in_array($this->extension, ['json', 'txt', 'xml']) || $this->ajax || $this->output->data->get('module', 'ajax');
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
			//TODO la partie query est inclue dans request ?
			header('Location: '.$_SERVER['REQUEST_URI']);
			$output = '';
		}

		$this->trigger('output', $output);
	}

	public function query($query = [])
	{
		if (method_exists($query, '__toArray'))
		{
			$query = $query->__toArray();
		}

		$url = $this->request;

		if ($query)
		{
			$url .= '?'.http_build_query($query);
		}

		return $url;
	}

	public function __toString()
	{
		return ($this->https ? 'https' : 'http').'://'.$this->host;
	}
}
