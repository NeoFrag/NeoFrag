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

class m_addons extends Module
{
	public $title         = 'Composants';
	public $description   = '';
	public $icon          = 'fa-puzzle-piece';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
	public $routes        = [
		//Modules
		'admin/module/{url_title}'        => '_module_settings',
		'admin/delete/module/{url_title}' => '_module_delete',
		
		//Thèmes
		'admin/theme/{url_title}'         => '_theme_settings',
		'admin/delete/theme/{url_title}'  => '_theme_delete',
		'admin/ajax/theme/active'         => '_theme_activation',
		'admin/ajax/theme/reset'          => '_theme_reset',
		'admin/ajax/theme/{url_title}'    => '_theme_settings',

		//Languages
		'admin/ajax/language/sort'        => '_language_sort'
	];
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/addons/addons.php
*/