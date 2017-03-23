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
		'label'       => 'Titre',
		'value'       => $this->form->value('title'),
		'type'        => 'text',
		'rules'       => 'required'
	],
	'type' => [
		'label'       => 'Type',
		'values'      => array_map(function($a){
			return $a['title'];
		}, $this->model('types')->get_types()),
		'value'       => $this->form->value('type_id'),
		'type'        => 'select',
		'size'        => 'col-md-3',
		'rules'       => 'required'
	],
	'date' => [
		'label'       => 'Date de début',
		'value'       => $this->form->value('date'),
		'type'        => 'datetime',
		'size'        => 'col-md-3',
		'rules'       => 'required'
	],
	'date_end' => [
		'label'       => 'Date de fin',
		'value'       => $this->form->value('date_end'),
		'type'        => 'datetime',
		'size'        => 'col-md-3',
		'description' => 'Laissez vide pour ne pas indiquer de durée'
	],
	'description' => [
		'label'       => 'Description',
		'value'       => $this->form->value('description'),
		'type'        => 'editor'
	],
	'private_description' => [
		'label'       => 'Description privée',
		'value'       => $this->form->value('private_description'),
		'type'        => 'editor',
		'description' => 'Seulement visible par les participants'
	],
	'location' => [
		'label'       => 'Lieu',
		'value'       => $this->form->value('location'),
		'type'        => 'textarea',
		'description' => 'Seulement visible par les participants'
	],
	'image' => [
		'label'       => 'Image',
		'value'       => $this->form->value('image_id'),
		'type'        => 'file',
		'upload'      => 'events',
		'info'        => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
		}
	],
	'published' => [
		'type'        => 'checkbox',
		'checked'     => ['on' => $this->form->value('published')],
		'values'      => ['on' => 'Publier']
	]
];

/*
NeoFrag Alpha 0.1.6
./modules/events/forms/events.php
*/