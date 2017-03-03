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

function NeoFrag()
{
	global $NeoFrag;
	return $NeoFrag;
}

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
define('NEOFRAG_VERSION', 'Alpha 0.1.5.3');

ini_set('default_charset', 'UTF8');
ini_set('mbstring.func_overload', 7);
mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');

spl_autoload_register(function($name){
	if ($override = substr($name = strtolower($name), 0, 2) == 'o_')
	{
		$name = substr($name, 2);
	}

	$dir = $override ? 'overrides' : 'neofrag';

	if (file_exists($file = $dir.'/'.($name == 'loader' ? 'core' : 'classes').'/'.$name.'.php'))
	{
		require_once $file;
	}
	else if (preg_match('/^(.+?)_(.+)/', $name, $match) && file_exists($dir.'/libraries/'.$match[1].'.php') && file_exists($file = $dir.'/libraries/'.$match[1].'s/'.$match[2].'.php'))
	{
		require_once $file;
	}
	else if (file_exists($file = $dir.'/libraries/'.$name.'.php'))
	{
		require_once $file;
	}
});

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

	if ($debug = NeoFrag() === NULL || !isset(NeoFrag()->user) || !isset(NeoFrag()->debug) || NeoFrag()->debug->is_enabled())
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
	'authenticators' => [
		'authenticators'
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
		'neofrag/themes/default/views',
		'overrides/views',
		'neofrag/views'
	],
	'widgets' => [
		'overrides/widgets',
		'neofrag/widgets',
		'widgets'
	]
]);

$NeoFrag->modules = $NeoFrag->themes = $NeoFrag->widgets = $NeoFrag->authenticators = $NeoFrag->css = $NeoFrag->js = $NeoFrag->js_load = $NeoFrag->modals = [];

$NeoFrag->module = $NeoFrag->theme = NULL;

foreach ([
			'array',
			'assets',
			'color',
			'countries',
			'file',
			'geolocalisation',
			'dir',
			'input',
			'location',
			'notify',
			'output',
			'statistics',
			'string',
			'time',
			'user_agent'
		] as $helper
	)
{
	$NeoFrag->helper($helper);
}

foreach([
			'debug',
			'output',
			'db',
			'url',
			'config',
			'access',
			'addons',
			'session',
			'user',
			'groups',
			'breadcrumb',
			'router'
		] as $library
	)
{
	$NeoFrag->{'core_'.$library};

	if ($library == 'config' && is_asset() && !preg_match('#^backups/#', $NeoFrag->url->request))
	{
		asset($NeoFrag->url->request);
	}
}

echo $NeoFrag->router()->output;

/*
NeoFrag Alpha 0.1.5.3
./index.php
*/