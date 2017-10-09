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
	protected $_langs      = FALSE;
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
		$this->_const['https']        = !empty($_SERVER['HTTPS']);
		$this->_const['location']     = ($this->https ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$url = parse_url($this->location);

		$this->_const['host']         = $url['host'];
		$this->_const['base']         = substr($_SERVER['SCRIPT_NAME'], 0, -9);//-strlen('index.php')
		$this->_const['ajax_header']  = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$this->_const['ajax_allowed'] = FALSE;

		if (substr($request = substr($url['path'], strlen($this->base)), -1) == '/')
		{
			header('Location: '.$this->base.substr($request, 0, -1));
			exit;
		}

		$segments = function($request) use ($config){
			$this->_const['request']           = $request;
			$this->_const['extension']         = extension($this->request);
			$this->_const['extension_allowed'] = $this->extension == '';
			$this->_const['segments']          = explode('/', $this->extension ? substr($this->request, 0, - strlen($this->extension) - 1) : $this->request ?: 'index');

			if (preg_match('/^(humans|robots)\.txt$/', $this->_const['request'], $match))
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

			if (\NEOFRAG_DEBUG_BAR || \NEOFRAG_LOGS)
			{
				$this->debug('URL', 'LOCATION', $this->location);
				$this->debug('URL', 'SEGMENTS', implode(' / ', $this->segments));
			}
		});

		$this->on('config_langs_listed', function($langs, &$lang) use (&$segments){
			if (array_key_exists($name = $this->segments[0], $langs))
			{
				$this->_langs = TRUE;
				$lang = $langs[$name];
				$segments(preg_replace('_^'.$name.'/?_', '', $this->request));
			}
			else
			{
				$this->on('config_lang_selected', function($lang){
					redirect($lang->info()->name.'/'.$this->request.$this->query);
				});
			}
		});

		$this->on('output_loaded', function(){
			return;
			//TODO 0.1.7
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
		if (substr($url, 0, 1) == '#')
		{
			return $url;
		}
		else if (substr($url, 0, 2) == '//')
		{
			$url = explode('/', substr($url, 2));

			array_unshift($url, implode('.', array_filter([array_shift($url), $this->_domain()])));

			return '//'.implode('/', $url);
		}
		else if (is_valid_url($url))
		{
			return $url;
		}

		$domain = '';//preg_match('/^.+\.neo/', $_SERVER['HTTP_HOST']) ? '//'.$this->_domain() : '';

		if (preg_match('/^addons\./', $_SERVER['HTTP_HOST']))
		{
			$url = preg_replace('_^shop/?_', '', $url);
		}
		else if (preg_match('/^my\./', $_SERVER['HTTP_HOST']))
		{
			$url = preg_replace('_^panel/?_', '', $url);
		}

		if ($this->_langs)
		{
			$url = $this->config->lang.'/'.$url;
		}

		return $domain.$this->base.rtrim($url, '/');
	}

	//TODO 0.1.7
	protected function _domain()
	{
		return preg_match('_neofr\.ag_', $_SERVER['HTTP_HOST']) ? 'neofr.ag' : 'neofrag';
	}

	public function ajax()
	{
		return 	in_array($this->extension, ['json', 'txt', 'xml']) ||
				$this->ajax ||
				($this->ajax_header && $this->ajax_allowed) ||
				($this->extension_allowed && $this->extension != '');
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

	public function extension($extension)
	{
		if (in_array($extension, ['json', 'xml', 'txt']))
		{
			if ($this->extension != $extension)
			{
				$this->error();
			}

			$this->_const['extension_allowed'] = TRUE;
			$this->_const['ajax_allowed']      = TRUE;
		}

		return $this;
	}

	public function redirect($location)
	{
		header('Location: '.url($location));
		$this->trigger('output');
	}

	public function refresh()
	{
		if ($this->ajax())
		{
			header('Content-Type: application/json; charset=UTF-8');
			$output = '{"success":"refresh"}';
		}
		else
		{
			header('Location: '.$_SERVER['REQUEST_URI'].$this->query);
			$output = '';
		}

		$this->trigger('output', $output);
	}
}
