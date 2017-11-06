<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'  => '{lang title}',
		'value'  => $this->form->value('title'),
		'type'   => 'text',
		'rules'  => 'required'
	],
	'category' => [
		'label'  => '{lang category}',
		'value'  => $this->form->value('category_id'),
		'values' => $this->form->value('categories'),
		'type'   => 'select',
		'rules'  => 'required'
	],
	'image' => [
		'label'  => '{lang upload}',
		'value'  => $this->form->value('image'),
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
		'label'   => '{lang description}',
		'value'   => $this->form->value('description'),
		'type'    => 'editor'
	],
	'published' => [
		'type'    => 'checkbox',
		'checked' => ['on' => $this->form->value('published')],
		'values'  => ['on' => '{lang album_visible}']
	]
];
