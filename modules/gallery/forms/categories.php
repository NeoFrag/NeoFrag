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

$rules = array(
	'title' => array(
		'label'  => 'Titre',
		'value'  => $title,
		'type'   => 'text',
		'rules'  => 'required'
	),
	'image' => array(
		'label'  => 'Image',
		'value'  => $image,
		'upload' => 'gallery/categories',
		'type'   => 'file',
		'info'   => ' d\'image (max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
		'check'  => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return 'Veuiller choisir un fichier d\'image';
			}
		}
	),
	'icon' => array(
		'label'  => 'Icône',
		'value'  => $icon,
		'upload' => 'gallery/categories',
		'type'   => 'file',
		'info'   => ' d\'image (format carré min. 16px et max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
		'check'  => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return 'Veuiller choisir un fichier d\'image';
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return 'L\'icône doit être carré';
			}
			else if ($w < 16)
			{
				return 'L\'icône doit faire au moins 16px';
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 16, 16);
		}
	)
);

/*
NeoFrag Alpha 0.1.1
./modules/gallery/forms/categories.php
*/