<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

define('NEOFRAG_MEMORY',  memory_get_usage());
define('NEOFRAG_TIME',    microtime(TRUE));
define('NEOFRAG_CMS',     __DIR__);
define('NEOFRAG_VERSION', 'Alpha 0.2.3');

error_reporting(E_ALL);

ini_set('error_log',       'logs/php.log');
ini_set('display_errors',  TRUE);
ini_set('default_charset', 'UTF-8');

if (file_exists('install/index.php'))
{
	if (file_exists('install/db.txt'))
	{
		define('NEOFRAG_INSTALL', TRUE);
	}
	else
	{
		require_once 'install/index.php';
	}
}

mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');

require_once 'config/neofrag.php';

function class_name($name)
{
	$name = explode('\\', $name);

	array_walk($name, function(&$a){
		if (in_array(strtolower($a), ['array', 'bool', 'default', 'float', 'int', 'list', 'null', 'print']))
		{
			$a .= '_';
		}
	});

	return implode('\\', $name);
}

function NeoFrag()
{
	static $NeoFrag;

	if ($args = func_get_args())
	{
		try
		{
			$class = new ReflectionClass(class_name(array_shift($args)));
		}
		catch (ReflectionException $e)
		{
			return;
		}

		if ($debug = NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
		{
			$memory = memory_get_usage();
			$time   = microtime(TRUE);
		}

		$object = $class->newInstanceArgs(array_shift($args) ?: []);

		if ($debug)
		{
			$object->__debug = (object)[
				'memory' => [$memory, memory_get_usage()],
				'time'   => [$time, microtime(TRUE)]
			];
		}

		if (!$NeoFrag)
		{
			$NeoFrag = $object;
		}

		return $object;
	}
	else
	{
		return $NeoFrag;
	}
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

foreach ([
			'array',
			'assets',
			'color',
			'countries',
			'debug',
			'file',
			'geolocalisation',
			'dir',
			'input',
			'location',
			'notify',
			'statistics',
			'string',
			'system',
			'time',
			'user_agent'
		] as $helper
	)
{
	require_once 'neofrag/helpers/'.$helper.'.php';
}

spl_autoload_register(function($name){
	$namespace = explode('\\', $name);

	if (array_shift($namespace) == 'NF' && $namespace)
	{
		array_walk($namespace, function(&$a){
			$a = strtolower(rtrim($a, '_'));
		});

		if (file_exists($file = implode('/', $namespace).'.php'))
		{
			require_once $file;
		}
	}
});

NeoFrag('NF\NeoFrag\NeoFrag')->__path(function($caller, $type, $file){
	$file = [$file];

	if (!in_array($type, ['addons', 'assets']))
	{
		if ($type)
		{
			array_unshift($file, $type);
		}

		array_unshift($file, 'neofrag');
	}

	$file = implode('/', $file);

	if (!NEOFRAG_SAFE_MODE)
	{
		yield 'overrides/'.$file;

		if (property_exists($caller, 'output') && ($theme = $caller->output->theme()))
		{
			yield 'themes/'.$theme->info()->name.'/overrides/'.$file;
		}
	}

	yield $file;
});

foreach ([
			'input',
			'debug',
			'url',
			'db',
			'access',
			'config',
			'output',
			'session',
			'groups'
		] as $core
	)
{
	NeoFrag()->{'core_'.$core};
}

define('NEOFRAG_CORE', TRUE);

if (defined('NEOFRAG_INSTALL'))
{
	require_once 'install/index.php';
}

NeoFrag()->output();
