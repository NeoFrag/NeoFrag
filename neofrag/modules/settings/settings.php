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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_settings extends Module
{
	public $title         = '{lang settings}';
	public $description   = '';
	public $icon          = 'fa-cogs';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $administrable = FALSE;
	public $deactivatable = FALSE;
	public $path          = __FILE__;
	public $routes        = array(
		'admin/ajax/themes/active'       => '_theme_activation',
		'admin/ajax/themes/install'      => '_theme_installation',
		'admin/ajax/themes/reset'        => '_theme_reset',
		'admin/ajax/themes/delete'       => '_theme_delete',
		'admin/ajax/themes/{url_title}'  => '_theme_internal',
		'admin/themes/{url_title}'       => '_theme_internal'
	);
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/settings/settings.php
*/