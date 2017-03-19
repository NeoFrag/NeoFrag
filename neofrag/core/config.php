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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Config extends Core
{
	private $_settings = [];
	private $_configs  = [];

	public function __construct()
	{
		parent::__construct();

		$this->reset();
	}

	public function reset()
	{
		$this->_configs = $this->_settings = [];

		if (($configs = $this->load->db->select('site', 'lang', 'name', 'value', 'type')->from('nf_settings')->get()) === NULL)
		{
			header('HTTP/1.0 503 Service Unavailable');
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
		}

		$this->update('');

		$nf_languages = $this->db	->select('code')
									->from('nf_settings_languages')
									->order_by('order')
									->get();

		//TODO
		$this->_configs['langs'] = array_unique(array_merge(array_intersect(array_filter(array_merge(/*[$this->session('language')], */preg_replace('/^(.+?)[;-].*/', '\1', explode(',', !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '')))), $nf_languages), $nf_languages));

		$this->update('default');

		$this->update('default', 'fr');
		//$this->update('default', array_shift($nf_languages));
	}

	public function __get($name)
	{
		if (isset($this->_configs[$name]))
		{
			return $this->_configs[$name];
		}

		return parent::__get($name);
	}

	public function __set($name, $value)
	{
		$this->_configs[$name] = $value;
	}

	public function __isset($name)
	{
		return isset($this->_configs[$name]);
	}

	public function __invoke($name, $value, $type = NULL)
	{
		if (isset($this->_configs[$name]))
		{
			NeoFrag()->db	->where('name', $name)
									->update('nf_settings', [
										'value' => $value
									]);

			if ($type)
			{
				NeoFrag()->db	->where('name', $name)
										->update('nf_settings', [
											'type' => $type
										]);
			}
		}
		else
		{
			NeoFrag()->db->insert('nf_settings', [
				'name'  => $name,
				'value' => $value,
				'type'  => $type ?: 'string'
			]);
		}

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

	public function debugbar()
	{
		return $this->debug->table($this->_configs);
	}
}

/*
NeoFrag Alpha 0.1.5.3
./neofrag/core/config.php
*/