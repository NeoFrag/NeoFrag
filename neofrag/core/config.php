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

class Config extends Core
{
	private $_settings = array();
	private $_configs  = array();

	public function __construct()
	{
		parent::__construct();
		
		$this->_configs['base_url']      = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		$this->_configs['request_url']   = $_SERVER['REQUEST_URI'] != $this->_configs['base_url'] ? substr($_SERVER['REQUEST_URI'], strlen($this->_configs['base_url'])) : 'index.html';
		$this->_configs['extension_url'] = extension($this->_configs['request_url'], $this->_configs['request_url']);

		$ext = extension($url = !empty($_GET['request_url']) ? $_GET['request_url'] : $this->_configs['request_url'], $url);
		$this->_configs['segments_url']  = explode('/', rtrim(substr($url, 0, - strlen($ext)), '.'));
		
		if ($this->_configs['segments_url'][0] == 'admin')
		{
			$this->_configs['admin_url'] = TRUE;
		}
		
		if ((empty($this->_configs['admin_url']) && $this->_configs['segments_url'][0] == 'ajax') || (!empty($this->_configs['admin_url']) && isset($this->_configs['segments_url'][1]) && $this->_configs['segments_url'][1] == 'ajax'))
		{
			$this->_configs['ajax_url'] = TRUE;
		}

		if (!$this->ajax && NeoFrag::loader()->assets->is_asset())
		{
			$this->assets($this->_configs['request_url']);
		}
		
		if (is_null($configs = NeoFrag::loader()->db->select('site, lang, name, value, type')->from('nf_settings')->get()))
		{
			exit('Database is empty');
		}
		
		foreach ($configs as $setting)
		{
			if ($setting['type'] == 'array')
			{
				$value = unserialize(utf8_html_entity_decode($setting['value']));
			}
			else if ($setting['type'] == 'list')
			{
				$value = explode('|', $setting['value']);
			}
			else if ($setting['type'] == 'bool')
			{
				$value = (bool)$setting['value'];
			}
			else if ($setting['type'] == 'int')
			{
				$value = (int)$setting['value'];
			}
			else
			{
				$value = $setting['value'];
			}

			$this->_settings[$setting['site']][$setting['lang']][$setting['name']] = $value;
			
			if (empty($site) && $setting['name'] == 'nf_domains' && in_string($_SERVER['HTTP_HOST'], $setting['value']))
			{
				$site = $value;
			}
		}
		
		$this->update('');
		$this->update('default');
		
		if (!empty($site))
		{
			$this->update('default');
		}
	}
	
	public function __get($name)
	{
		if (isset($this->_configs[$name]))
		{
			return $this->_configs[$name];
		}
		
		return NULL;
	}
	
	public function __set($name, $value)
	{
		$this->_configs[$name] = $value;
	}
	
	public function __invoke($name, $value)
	{
		NeoFrag::loader()->db	->where('name', $name)
								->update('nf_settings', array(
									'value' => $value
								));

		$this->_configs[$name] = $value;

		return $this;
	}

	public function update($site = '', $lang = '')
	{
		$this->_configs['lang'] = $lang;
		$this->_configs['site'] = $site;

		if (!empty($this->_settings[$site][$lang]))
		{
			foreach ($this->_settings[$site][$lang] as $name => $value)
			{
				$this->_configs[$name] = $value;
			}
		}
	}

	public function profiler()
	{
		if (empty($this->_configs))
		{
			return '';
		}

		ksort($this->_configs);

		$output = '	<a href="#" data-profiler="config"><i class="icon-chevron-'.($this->session('profiler', 'config') ? 'down' : 'up').' pull-right"></i></a>
					<h2>Config</h2>
					<div class="profiler-block">'.NeoFrag::loader()->profiler->table($this->_configs).'</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/config.php
*/