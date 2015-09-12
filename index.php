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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

function include_class($file)
{
	require_once $file;

	if (file_exists($overide = str_replace('./neofrag/', './overrides/', $file)))
	{
		require_once $overide;
	}
}

ob_start();

define('NEOFRAG_CMS',     dirname(__FILE__));
define('NEOFRAG_MEMORY',  memory_get_usage(TRUE));
define('NEOFRAG_TIME',    microtime(TRUE));
define('NEOFRAG_VERSION', 'Alpha 0.1.1');

ini_set('default_charset', 'UTF8');
ini_set('mbstring.func_overload', 7);
mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');

//Appel des classes de base
include_class('./neofrag/classes/neofrag.php');
include_class('./neofrag/classes/library.php');
include_class('./neofrag/classes/controller.php');
include_class('./neofrag/classes/controller_module.php');
include_class('./neofrag/classes/controller_widget.php');
include_class('./neofrag/classes/core.php');
include_class('./neofrag/classes/zone.php');
include_class('./neofrag/classes/row.php');
include_class('./neofrag/classes/col.php');
include_class('./neofrag/classes/panel.php');
include_class('./neofrag/classes/panel_box.php');
include_class('./neofrag/classes/button_back.php');
include_class('./neofrag/classes/driver.php');
include_class('./neofrag/classes/model.php');
include_class('./neofrag/classes/module.php');
include_class('./neofrag/classes/theme.php');
include_class('./neofrag/classes/widget.php');
include_class('./neofrag/classes/widget_view.php');

//Appel de la librairie Loader
include_class('./neofrag/core/loader.php');

//Création du loader de base
$NeoFrag = new Loader(
	array(
		'assets' => array(
			'./assets',
			'./overrides/themes/default',
			'./neofrag/themes/default'
		),
		'config' => array(
			'./neofrag/config',
			'./overrides/config',
			'./config'
		),
		'core' => array(
			'./overrides/core',
			'./neofrag/core'
		),
		'helpers' => array(
			'./overrides/helpers',
			'./neofrag/helpers'
		),
		'lang' => array(
			'./overrides/lang',
			'./neofrag/lang'
		),
		'libraries' => array(
			'./overrides/libraries',
			'./neofrag/libraries',
		),
		'modules' => array(
			'./overrides/modules',
			'./neofrag/modules',
			'./modules'
		),
		'themes' => array(
			'./overrides/themes',
			'./neofrag/themes',
			'./themes'
		),
		'views' => array(
			'./overrides/themes/default/views',
			'./neofrag/themes/default/views'
		),
		'widgets' => array(
			'./overrides/widgets',
			'./neofrag/widgets',
			'./widgets'
		)
	)
);

//Chargement des librairies
$NeoFrag->core(
	'error',
	'template',
	'profiler',
	'database',
	'config',
	'addons',
	'session',
	'user',
	'language',
	'groups',
	'router',
	'output'
);

/*
NeoFrag Alpha 0.1.1
./index.php
*/