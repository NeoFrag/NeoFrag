<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'       => 'Titre',
		'value'       => $this->form()->value('title'),
		'type'        => 'text',
		'rules'       => 'required'
	],
	'type' => [
		'label'       => 'Type',
		'values'      => array_map(function($a){
			return $a['title'];
		}, $this->model('types')->get_types()),
		'value'       => $this->form()->value('type_id'),
		'type'        => 'select',
		'size'        => 'col-3',
		'rules'       => 'required'
	],
	'date' => [
		'label'       => 'Date de début',
		'value'       => $this->form()->value('date'),
		'type'        => 'datetime',
		'size'        => 'col-3',
		'rules'       => 'required'
	],
	'date_end' => [
		'label'       => 'Date de fin',
		'value'       => $this->form()->value('date_end'),
		'type'        => 'datetime',
		'size'        => 'col-3',
		'description' => 'Laissez vide pour ne pas indiquer de durée'
	],
	'description' => [
		'label'       => 'Description',
		'value'       => $this->form()->value('description'),
		'type'        => 'editor'
	],
	'private_description' => [
		'label'       => 'Description privée',
		'value'       => $this->form()->value('private_description'),
		'type'        => 'editor',
		'description' => 'Seulement visible par les participants'
	],
	'location' => [
		'label'       => 'Lieu',
		'value'       => $this->form()->value('location'),
		'type'        => 'textarea',
		'description' => 'Seulement visible par les participants'
	],
	'image' => [
		'label'       => 'Image',
		'value'       => $this->form()->value('image_id'),
		'type'        => 'file',
		'upload'      => 'events',
		'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		}
	],
	'published' => [
		'type'        => 'checkbox',
		'checked'     => ['on' => $this->form()->value('published')],
		'values'      => ['on' => 'Publier']
	]
];
