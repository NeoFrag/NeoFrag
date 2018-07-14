<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'       => $this->lang('Titre'),
		'value'       => $this->form()->value('title'),
		'type'        => 'text',
		'rules'       => $this->lang('required')
	],
	'type' => [
		'label'       => $this->lang('Type'),
		'values'      => array_map(function($a){
			return $a['title'];
		}, $this->model('types')->get_types()),
		'value'       => $this->form()->value('type_id'),
		'type'        => 'select',
		'size'        => 'col-3',
		'rules'       => $this->lang('required')
	],
	'date' => [
		'label'       => $this->lang('Date de début'),
		'value'       => $this->form()->value('date'),
		'type'        => 'datetime',
		'size'        => 'col-3',
		'rules'       => $this->lang('required')
	],
	'date_end' => [
		'label'       => $this->lang('Date de fin'),
		'value'       => $this->form()->value('date_end'),
		'type'        => 'datetime',
		'size'        => 'col-3',
		'description' => $this->lang('Laissez vide pour ne pas indiquer de durée')
	],
	'description' => [
		'label'       => $this->lang('Description'),
		'value'       => $this->form()->value('description'),
		'type'        => 'editor'
	],
	'private_description' => [
		'label'       => $this->lang('Description privée'),
		'value'       => $this->form()->value('private_description'),
		'type'        => 'editor',
		'description' => $this->lang('Seulement visible par les participants')
	],
	'location' => [
		'label'       => $this->lang('Lieu'),
		'value'       => $this->form()->value('location'),
		'type'        => 'textarea',
		'description' => $this->lang('Seulement visible par les participants')
	],
	'image' => [
		'label'       => $this->lang('Image'),
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
		'values'      => ['on' => $this->lang('Publier')]
	]
];
