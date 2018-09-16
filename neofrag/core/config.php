<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Config extends Core
{
	protected $_const = [];

	public function __construct()
	{
		$settings = [];

		foreach ($this->db->select('site', 'lang', 'name', 'value', 'type')->from('nf_settings')->get() as $setting)
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

			$settings[$setting['site']][$setting['lang']][$setting['name']] = $value;
		}

		$load = function($site = '', $lang = '') use (&$settings){
			$this->_const['lang'] = $lang;
			$this->_const['site'] = $site;

			if ($lang)
			{
				$lang = $lang->info()->name;
			}

			if (!empty($settings[$site][$lang]))
			{
				foreach ($settings[$site][$lang] as $name => $value)
				{
					$this->_const[$name] = $value;
				}
			}
		};

		$load();

		if ($this->url->subdomain)
		{
			$load($this->url->subdomain);
		}

		$this->on('session_init', function($session) use (&$load){
			$n = 0;
			$langs = [];

			foreach ($this->model2('addon')->get('language') as $lang)
			{
				if ($lang->is_enabled())
				{
					$n++;
					$langs[$lang->info()->name] = $lang;
				}
			}

			$main_lang = NULL;

			if ($n > 1)
			{
				uasort($langs, function($a, $b){
					return strnatcmp($a->settings()->order, $b->settings()->order);
				});

				$this->trigger('config_langs_listed', $langs, $main_lang);

				if (!$main_lang)
				{
					if (($this->user() && ($addon = $this->user->language->addon()) && isset($langs[$name = $addon->info()->name])) || isset($langs[$name = $session('language')]))
					{
						$main_lang = $langs[$name];
					}
					else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && preg_match_all('/([a-zA-Z-]+)(?:;q=([0-9.]+))?,?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches, PREG_SET_ORDER))
					{
						$accepted = [];

						foreach ($matches as $match)
						{
							$accepted[$match[1]] = isset($match[2]) ? (float)$match[2] : 1;
						}

						arsort($accepted);

						foreach ($accepted as $name => $q)
						{
							if (isset($langs[$name]))
							{
								$main_lang = $langs[$name];
								break;
							}
						}
					}
				}
			}

			if (!$main_lang)
			{
				$main_lang = reset($langs);
			}

			$load('', $main_lang);

			$this->_const['langs'] = [];

			if ($n > 1)
			{
				unset($langs[$this->_const['lang']->info()->name]);
				array_unshift($langs, $main_lang);
				$this->_const['langs'] = array_values($langs);

				if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
				{
					$this->debug('LANGS', implode(' / ', array_map(function($a){
						return strtoupper($a->info()->name);
					}, $langs)));
				}
			}

			$this->trigger('config_lang_selected');

			setlocale(LC_ALL, $main_lang->locale());

			$this->trigger('config_init');
		});

		$this->debug->bar('settings', function(){
			return $this->_const;
		});
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_const))
		{
			return $this->_const[$name];
		}

		return parent::__get($name);
	}

	public function __set($name, $value)
	{
		$this->_const[$name] = $value;
	}

	public function __call($name, $args)
	{
		if ($name == 'unset')
		{
			if (array_key_exists($args[0], $this->_const))
			{
				unset($this->_const[$args[0]]);

				NeoFrag()->db	->where('name', $args[0])
								->delete('nf_settings');
			}

			return $this;
		}

		return parent::__call($name, $args);
	}


	public function __isset($name)
	{
		return array_key_exists($name, $this->_const);
	}

	public function __invoke($name, $value, $type = NULL)
	{
		if (array_key_exists($name, $this->_const))
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

		$this->_const[$name] = $value;

		return $this;
	}
}
