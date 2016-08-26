<?php
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

function check_file($dir, $force = FALSE)
{
	if ($dir === '')
	{
		return FALSE;
	}

	static $cache;

	if (!isset($cache[$dir]) || $force)
	{
		$dirs = explode('/', $dir);

		$exists = TRUE;

		foreach (array_keys($dirs) as $i)
		{
			if (!isset($cache[$path = implode('/', array_slice($dirs, 0, $i + 1))]) || $force)
			{
				$cache[$path] = $exists ? file_exists($path) : FALSE;
			}
			
			$exists = $cache[$path];
		}
	}

	return $cache[$dir];
}

ob_start();

define('NEOFRAG_CMS',     dirname(__FILE__));
define('NEOFRAG_MEMORY',  memory_get_peak_usage());
define('NEOFRAG_TIME',    microtime(TRUE));
define('NEOFRAG_VERSION', 'Alpha 0.1.4.2');

ini_set('default_charset', 'UTF8');
ini_set('mbstring.func_overload', 7);
mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');

function __autoload($name)
{
	if ($override = substr($name = strtolower($name), 0, 2) == 'o_')
	{
		$name = substr($name, 2);
	}

	if (file_exists($file = ($override ? 'overrides' : 'neofrag').'/'.($name == 'loader' ? 'core' : 'classes').'/'.$name.'.php'))
	{
		require_once $file;
	}
}

function load($name)
{
	$args = array_slice(func_get_args(), 1);

	$override = FALSE;

	if (substr($name, 0, 2) == 'o_')
	{
		$override = TRUE;
	}
	else if (class_exists('o_'.$name))
	{
		$name = 'o_'.$name;
		
		$override = TRUE;
	}

	$r = new ReflectionClass($name);

	if ($debug = NeoFrag::loader() === NULL || NeoFrag::loader()->config === NULL || NeoFrag::loader()->user === NULL || NeoFrag::loader()->debug === NULL || NeoFrag::loader()->debug->is_enabled())
	{
		$memory = memory_get_usage();
		$time   = microtime(TRUE);
	}
	
	$object = $r->newInstanceArgs($args);
	
	if ($debug)
	{
		$object->memory = [$memory, memory_get_usage()];
		$object->time   = [$time, microtime(TRUE)];

		if ($override)
		{
			$object->override = TRUE;
		}
	}

	return $object;
}

$NeoFrag = load('loader', [
	'assets' => [
		'assets',
		'overrides/themes/default',
		'neofrag/themes/default'
	],
	'config' => [
		'overrides/config',
		'neofrag/config',
		'config'
	],
	'core' => [
		'overrides/core',
		'neofrag/core'
	],
	'helpers' => [
		'overrides/helpers',
		'neofrag/helpers'
	],
	'lang' => [
		'overrides/lang',
		'neofrag/lang'
	],
	'libraries' => [
		'overrides/libraries',
		'neofrag/libraries',
	],
	'modules' => [
		'overrides/modules',
		'neofrag/modules',
		'modules'
	],
	'themes' => [
		'overrides/themes',
		'neofrag/themes',
		'themes'
	],
	'views' => [
		'overrides/themes/default/views',
		'neofrag/themes/default/views'
	],
	'widgets' => [
		'overrides/widgets',
		'neofrag/widgets',
		'widgets'
	]
]);

$NeoFrag->modules = $NeoFrag->themes = $NeoFrag->widgets = $NeoFrag->css = $NeoFrag->js = $NeoFrag->js_load = [];

$NeoFrag->module = $NeoFrag->theme = NULL;

foreach (['array', 'assets', 'buttons', 'color', 'file', 'geolocalisation', 'i18n', 'input', 'location', 'notify', 'output', 'statistics', 'string', 'time', 'user_agent'] as $helper)
{
	$NeoFrag->helper($helper);
}

foreach(['debug', 'template', 'db', 'config', 'access', 'addons', 'session', 'user', 'groups', 'breadcrumb', 'router', 'output'] as $library)
{
	$NeoFrag->{'core_'.$library};

	if ($library == 'config' && is_asset())
	{
		asset($NeoFrag->config->request_url);
	}
}

$NeoFrag	->router->exec()
			->output->display();

/*
NeoFrag Alpha 0.1.4.2
./index.php
*/