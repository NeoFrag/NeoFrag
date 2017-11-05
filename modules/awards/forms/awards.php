<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'name' => [
		'label' => 'Titre de l\'événement',
		'value' => $this->form->value('name'),
		'type'  => 'text',
		'rules' => 'required'
	],
	'location' => [
		'label' => 'Lieu',
		'icon'  => 'fa-map-marker',
		'value' => $this->form->value('location'),
		'type'  => 'text'
	],
	'date' => [
		'label' => 'Date',
		'value' => $this->form->value('date') ? timetostr($this->lang('date_short'), strtotime($this->form->value('date'))) : '',
		'type'  => 'date',
		'check' => function($value){
			if ($value && strtotime($value) > strtotime(date('Y-m-d')))
			{
				return $this->lang('invalid_birth_date');
			}
		},
		'size'  => 'col-md-3',
		'rules' => 'required'
	],
	'team' => [
		'label'  => 'Équipe',
		'value'  => $this->form->value('team_id'),
		'values' => $this->form->value('teams'),
		'type'   => 'select',
		'size'   => 'col-md-4',
		'rules'  => 'required'
	],
	'game' => [
		'label'  => 'Jeu',
		'value'  => $this->form->value('game_id'),
		'values' => $this->form->value('games'),
		'type'   => 'select',
		'size'   => 'col-md-4',
		'rules'  => 'required'
	],
	'platform' => [
		'label'  => 'Plateforme',
		'icon'   => 'fa-tv',
		'value'  => $this->form->value('platform'),
		'values' => [
			'PC'       => 'PC',
			'PS3'      => 'PS3',
			'PS4'      => 'PS4',
			'Wii'      => 'Wii',
			'Wii U'    => 'Wii U',
			'Xbox 360' => 'Xbox 360',
			'Xbox One' => 'Xbox One',
		],
		'type'   => 'select',
		'size'   => 'col-md-2',
		'rules'  => 'required'
	],
	'ranking' => [
		'label' => 'Classement',
		'icon'  => 'fa-trophy',
		'value' => $this->form->value('ranking'),
		'type'  => 'number',
		'size'  => 'col-md-2',
		'rules' => 'required'
	],
	'participants' => [
		'label' => 'Nombre d\'équipes',
		'icon'  => 'fa-users',
		'value' => $this->form->value('participants'),
		'type'  => 'number',
		'size'  => 'col-md-2',
		'rules' => 'required'
	],
	'description' => [
		'label' => 'Commentaire',
		'value' => $this->form->value('description'),
		'type'  => 'editor'
	],
	'image' => [
		'label'  => 'Image',
		'value'  => $this->form->value('image'),
		'type'   => 'file',
		'upload' => 'awards',
		'info'   => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'  => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
		}
	]
];
