<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'         => 'Titre',
		'value'         => $this->form()->value('title'),
		'type'          => 'text',
		'rules'			=> 'required'
	],
	'game' => [
		'label'         => $this->lang('Jeux'),
		'value'         => $this->form()->value('game_id'),
		'values'        => $this->form()->value('games'),
		'type'          => 'select',
		'rules'			=> 'required'
	],
	'image' => [
		'label'       => $this->lang('Bannière'),
		'value'       => $this->form()->value('image_id'),
		'type'        => 'file',
		'upload'      => 'teams',
		'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		}
	],
	'icon' => [
		'label'       => $this->lang('Logo'),
		'value'       => $this->form()->value('icon_id'),
		'upload'      => 'teams/icons',
		'type'        => 'file',
		'info'        => $this->lang(' d\'image (format carré min. %dpx et max. %d Mo)', 16, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}

			list($w, $h) = getimagesize($filename);

			if ($w != $h)
			{
				return $this->lang('Le logo doit être carré');
			}
			else if ($w < 16)
			{
				return $this->lang('Le logo doit faire au moins %dpx', 16);
			}
		}
	],
	'description' => [
		'label' => $this->lang('Description'),
		'value' => $this->form()->value('description'),
		'type'  => 'editor'
	]
];
