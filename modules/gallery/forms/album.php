<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'  => $this->lang('Titre'),
		'value'  => $this->form()->value('title'),
		'type'   => 'text',
		'rules'  => 'required'
	],
	'category' => [
		'label'  => $this->lang('Catégorie'),
		'value'  => $this->form()->value('category_id'),
		'values' => $this->form()->value('categories'),
		'type'   => 'select',
		'rules'  => 'required'
	],
	'image' => [
		'label'  => $this->lang('Affiche'),
		'value'  => $this->form()->value('image'),
		'type'   => 'file',
		'upload' => 'gallery/covers',
		'info'   => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'  => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		}
	],
	'description' => [
		'label'   => $this->lang('Description'),
		'value'   => $this->form()->value('description'),
		'type'    => 'editor'
	],
	'published' => [
		'type'    => 'checkbox',
		'checked' => ['on' => $this->form()->value('published')],
		'values'  => ['on' => $this->lang('Album visible dans la galerie')]
	]
];
