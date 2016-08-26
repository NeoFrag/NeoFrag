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

class m_pages extends Module
{
	public $title       = '{lang pages}';
	public $description = '';
	public $icon        = 'fa-file-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $routes      = [
		//Index
		'{url_title}'             => '_index',
		
		//Admin
		'admin{pages}'            => 'index',
		'admin/{id}/{url_title*}' => '_edit'
	];
	
	public function get_title($new_title = NULL)
	{
		if (!empty($this->load->data['module_title']))
		{
			return $this->load->data['module_title'];
		}

		/* TODO
			Bug dans la liste des modules quand un module est désactivé et que le module Page est activé
			return parent::get_title($new_title);
		*/

		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->load->lang($this->title, NULL);
		}
		
		return $title;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/pages/pages.php
*/