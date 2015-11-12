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
		'label'       => '{lang game_title}',
		'value'       => $title,
		'type'        => 'text',
		'rules'       => 'required'
	),
	'parent_id' => array(
		'label'       => '{lang parent_game}',
		'value'       => $parent_id,
		'values'      => $games,
		'type'        => 'select'
	),
	'image' => array(
		'label'       => '{lang banner}',
		'value'       => $image_id,
		'upload'      => 'games',
		'type'        => 'file',
		'info'        => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return i18n('select_image_file');
			}
		}
	),
	'icon' => array(
		'label'       => '{lang icon}',
		'value'       => $icon_id,
		'upload'      => 'games/icons',
		'type'        => 'file',
		'info'        => i18n('file_icon', 16, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return i18n('select_image_file');
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return i18n('icon_must_be_square');
			}
			else if ($w < 16)
			{
				return i18n('icon_size_error', 16);
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 16, 16);
		}
	)
);

/*
NeoFrag Alpha 0.1
./modules/games/forms/games.php
*/