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
		'label'         => 'Titre',
		'value'         => $this->form->value('title'),
		'type'          => 'text',
		'rules'			=> 'required'
	],
	'game' => [
		'label'         => '{lang game}',
		'value'         => $this->form->value('game_id'),
		'values'        => $this->form->value('games'),
		'type'          => 'select',
		'rules'			=> 'required'
	],
	'image' => [
		'label'       => '{lang image}',
		'value'       => $this->form->value('image_id'),
		'type'        => 'file',
		'upload'      => 'teams',
		'info'        => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
		}
	],
	'icon' => [
		'label'       => '{lang icon}',
		'value'       => $this->form->value('icon_id'),
		'upload'      => 'teams/icons',
		'type'        => 'file',
		'info'        => $this->lang('file_icon', 16, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return $this->lang('icon_must_be_square');
			}
			else if ($w < 16)
			{
				return $this->lang('icon_size_error', 16);
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 16, 16);
		}
	],
	'description' => [
		'label' => '{lang description}',
		'value' => $this->form->value('description'),
		'type'  => 'editor'
	]
];

/*
NeoFrag Alpha 0.1.5.2
./modules/teams/forms/teams.php
*/