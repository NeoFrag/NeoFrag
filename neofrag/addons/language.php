<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Addons;

use NF\NeoFrag\Loadables\Addon;

abstract class Language extends Addon
{
	static public function __class($name)
	{
		return 'Addons\\language_'.$name.'\\language_'.$name;
	}

	static public function __label()
	{
		return ['Langues', 'Langue', 'fa-flag', 'danger'];
	}

	static public function get($caller, $name)
	{
		static $locales = [];

		if (!isset($locales[$caller_name = get_class($caller)][$name]))
		{
			$locales[$caller_name][$name] = NULL;

			foreach (\NeoFrag()->config->langs as $lang)
			{
				if ($locale = $lang->_get($caller, $name))
				{
					$locales[$caller_name][$name] = [$locale, $lang];
					break;
				}
			}
		}

		return $locales[$caller_name][$name];
	}

	abstract public function locale();
	abstract public function date2sql(&$date);
	abstract public function time2sql(&$time);
	abstract public function datetime2sql(&$datetime);

	public function __actions()
	{
		return [
			['enable',   'Activer',       'fa-check',   'success'],
			['disable',  'Désactiver',    'fa-times',   'muted'],
			['settings', 'Configuration', 'fa-wrench',  'warning'],
			NULL,
			['reset',    'Réinitialiser', 'fa-refresh', 'danger'],
			['delete',   'Désinstaller',  'fa-remove',  'danger']
		];
	}

	protected function _get($caller, $name)
	{
		$paths = [];

		$callback = function(&$path) use ($name){
			if (check_file($path))
			{
				$locales = include $path;

				if (array_key_exists($name, $locales))
				{
					$path = $locales[$name];
					return TRUE;
				}
			}
		};

		if ($locale = $caller->__path('langs', $this->info()->name.'.php', $paths, $callback))
		{
			return $locale;
		}

		trigger_error('Unfound lang: '.$name.' in paths ['.implode(';', $paths).']', E_USER_WARNING);
	}
}
