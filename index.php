<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
define('NEOFRAG_VERSION', 'Alpha 0.1.6.1');

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

	if (file_exists($file = $dir.'/classes/'.$name.'.php'))
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

if (isset($NeoFrag->config->nf_dispositions_upgrade) && !$NeoFrag->config->nf_dispositions_upgrade)
{
	foreach ($NeoFrag->db->from('nf_dispositions')->get() as $disposition)
	{
		$rows = unserialize(preg_replace('/O:\d+:"(Row|Col|Widget_View)"/', 'O:8:"stdClass"', preg_replace_callback('/s:\d+:"(.(?:Row|Col|Widget_View).+?)";/', function($a){
			return 's:'.strlen($a = preg_replace('/.*_(.+?)$/', '\1', $a[1])).':"'.$a.'";';
		}, $disposition['disposition'])));

		$new_disposition = [];

		foreach ($rows as $row)
		{
			$cols = [];

			if (!empty($row->cols))
			{
				foreach ($row->cols as $col)
				{
					$widgets = [];

					if (!empty($col->widgets))
					{
						foreach ($col->widgets as $widget)
						{
							$new_widget = $NeoFrag->panel_widget($widget->widget_id);

							if (!empty($widget->style))
							{
								$new_widget->color(str_replace('panel-', '', $widget->style));
							}

							$widgets[] = $new_widget;
						}
					}

					$new_col = call_user_func_array([$NeoFrag, 'col'], $widgets);

					if (!empty($col->size))
					{
						$new_col->size($col->size);
					}

					$cols[] = $new_col;
				}
			}

			$new_row = call_user_func_array([$NeoFrag, 'row'], $cols);

			if (!empty($row->style))
			{
				$new_row->style($row->style);
			}

			$new_disposition[] = $new_row;
		}

		$NeoFrag->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => serialize($new_disposition)
						]);

		$NeoFrag->config('nf_dispositions_upgrade', TRUE, 'bool');
	}

	dir_create('authenticators');

	foreach (['facebook', 'twitter', 'google', 'battle_net', 'steam', 'twitch', 'github', 'linkedin'] as $name)
	{
		$NeoFrag->network('https://raw.githubusercontent.com/NeoFragCMS/neofrag-cms/master/authenticators/'.$name.'.php')->stream('authenticators/'.$name.'.php');
	}
}

echo $NeoFrag->router()->output;
