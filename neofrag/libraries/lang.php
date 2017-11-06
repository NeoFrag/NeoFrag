<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Lang extends Library
{
	static protected $_objects = [];

	protected $_name;
	protected $_args;
	protected $_locale;

	public function __invoke($name)
	{
		//TODO	->lang('{lang ...}', NULL);

		if (is_object($name))
		{
			return $name;
		}

		if (!isset(static::$_objects[$caller = get_class($this->__caller)][$key = serialize($args = func_get_args())]))
		{
			$this->_args = $args;
			$this->_name = array_shift($this->_args);

			static::$_objects[$caller][$key] = $this;
		}

		return static::$_objects[$caller][$key];
	}

	public function __toString()
	{
		if ($this->_locale === NULL)
		{
			$locale = $this->_name;
			$args   = $this->_args;

			if (($language = isset($this->__caller->info()->language) ? $this->__caller->info()->language : 'fr') != $this->config->lang)
			{
				if ($lang = $this->config->langs[0]->static_get($this->__caller, $key = hash('crc32b', $this->_name)))
				{
					list($locale, $lang) = $lang;
				}
				else
				{
					if (NEOFRAG_LOGS_I18N && !$this->db()->select('1')->from('nf_log_i18n')->where('language', $language)->where('key', $key)->where('file', $class = get_class($this->__caller))->row())
					{
						NeoFrag()->model2('log_i18n')->set('language', $language)->set('key', $key)->set('locale', $locale)->set('file', $class)->create();
					}

					trigger_error('Unfound lang: '.$locale, E_USER_WARNING);
				}
			}

			if ($args)
			{
				if (in_string('|', $locale))
				{
					$n      = NULL;
					$locale = explode('|', $locale);
					$count  = count($locale);

					foreach ($locale as $i => &$l)
					{
						if (preg_match('/^\{(\d+?)\}|\[(\d+?),(\d+?|Inf)\]/', $l, $match))
						{
							$n = end($match);

							if ($n == 'Inf')
							{
								break;
							}
						}
						else if ($n === NULL)
						{
							$l = '[0,1]'.$l;
							$n = 1;
						}
						else if ($i == $count - 1)
						{
							$l = '['.++$n.',Inf]'.$l;
						}
						else
						{
							$l = '{'.++$n.'}'.$l;
						}

						unset($l);
					}

					foreach ($locale as $l)
					{
						if (preg_match('/^\{(\d+)\}(.+)/', $l, $match) && $args[0] == $match[1])
						{
							$locale = $match[2];
							unset($args[0]);
							break;
						}
						else if (preg_match('/^\[(\d+),(\d+|Inf)\](.+)/', $l, $match) && $args[0] >= $match[1] && ($match[2] == 'Inf' || $args[0] <= $match[2]))
						{
							$locale = $match[3];
							unset($args[0]);
							break;
						}
					}
				}

				array_unshift($args, $locale);
				$locale = call_user_func_array('sprintf', $args);
			}

			if (NEOFRAG_LOGS_I18N)
			{
				if (!isset($lang))
				{
					$lang = NeoFrag()->model2('addon')->get('language', $language);
				}

				$locale = $lang->info()->icon.$locale;
			}

			$this->_locale = $locale;
		}

		return $this->_locale;
	}
}
