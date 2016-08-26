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

class w_gallery extends Widget
{
	public $title       = '{lang galleries}';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Jérémy Valentin <jeremy.valentin@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $types       = [
		'index'  => '{lang categories_list}',
		'albums' => '{lang albums_from_category}',
		'image'  => '{lang random_picture}',
		'slider' => '{lang album_slide}'
	];
}

/*
NeoFrag Alpha 0.1.3
./widgets/gallery/gallery.php
*/