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

$rules = [
	'title' => [
		'label'  => '{lang title}',
		'value'  => $title,
		'type'   => 'text',
		'rules'  => 'required',
	],
	'category' => [
		'label'  => '{lang category}',
		'value'  => $category_id,
		'values' => $categories,
		'type'   => 'select',
		'rules'  => 'required'
	],
	'image' => [
		'label'  => '{lang upload}',
		'value'  => $image,
		'type'   => 'file',
		'upload' => 'gallery/covers',
		'info'   => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'  => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return i18n('select_image_file');
			}
		}
	],
	'description' => [
		'label'   => '{lang description}',
		'value'   => $description,
		'type'    => 'editor'
	],
	'published' => [
		'type'    => 'checkbox',
		'checked' => ['on' => $published],
		'values'  => ['on' => '{lang album_visible}']
	]
];

/*
NeoFrag Alpha 0.1.3
./modules/gallery/forms/album.php
*/