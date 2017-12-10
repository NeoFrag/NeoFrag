<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'         => $this->lang('title'),
		'value'         => $this->form->value('title'),
		'type'          => 'text',
		'rules'			=> 'required'
	],
	'category' => [
		'label'         => $this->lang('category'),
		'value'         => $this->form->value('category_id'),
		'values'        => $this->form->value('categories'),
		'type'          => 'select',
		'rules'			=> 'required'
	],
	'image' => [
		'label'       => $this->lang('image'),
		'value'       => $this->form->value('image_id'),
		'type'        => 'file',
		'upload'      => 'news',
		'info'        => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
		}
	],
	'introduction' => [
		'label'			=> $this->lang('intro'),
		'value'			=> $this->form->value('introduction'),
		'type'			=> 'editor',
		'rules'			=> 'required'
	],
	'content' => [
		'label'			=> $this->lang('content'),
		'value'			=> $this->form->value('content'),
		'type'			=> 'editor'
	],
	'tags' => [
		'label'			=> $this->lang('tags'),
		'value'			=> $this->form->value('tags'),
		'type'			=> 'text'
	],
	'published' => [
		'type'			=> 'checkbox',
		'checked'		=> ['on' => $this->form->value('published')],
		'values'        => ['on' => $this->lang('published')]
	]
];
