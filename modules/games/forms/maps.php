<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'game_id' => [
		'label'  => 'Jeu',
		'value'  => $this->form->value('game_id'),
		'values' => $this->form->value('games'),
		'type'   => 'select',
		'rules'  => 'required'
	],
	'title' => [
		'label' => 'Nom de la carte',
		'value' => $this->form->value('title'),
		'type'  => 'text',
		'rules' => 'required'
	],
	'image' => [
		'label' => 'Image',
		'value' => $this->form->value('image_id'),
		'upload'=> 'games/maps',
		'type'  => 'file',
		'info'  => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'  => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
		}
	]
];
