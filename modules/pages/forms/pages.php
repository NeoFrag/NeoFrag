<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'         => $this->lang('page_title'),
		'value'         => $this->form->value('title'),
		'type'          => 'text',
		'rules'         => 'required'
	],
	'subtitle' => [
		'label'         => $this->lang('subtitle'),
		'value'         => $this->form->value('subtitle'),
		'type'          => 'text'
	],
	'name' => [
		'label'         => $this->lang('access_path'),
		'value'         => $name = $this->form->value('name'),
		'type'          => 'text',
		'check'         => function($value, $post) use ($name){
			if (!$value)
			{
				$value = $post['title'];
			}

			$value = url_title($value);

			if ($value != $name && NeoFrag()->db->select('1')->from('nf_pages')->where('name', $value)->row())
			{
				return $this->lang('access_path_already_used');
			}
		}
	],
	'content' => [
		'label'			=> $this->lang('content'),
		'value'			=> $this->form->value('content'),
		'type'			=> 'editor'
	],
	'published' => [
		'type'			=> 'checkbox',
		'checked'		=> ['on' => $this->form->value('published')],
		'values'        => ['on' => $this->lang('publish_now')]
	]
];
