<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => $this->lang('title'),
		'value' => $this->form->value('title'),
		'type'  => 'text',
		'rules' => 'required'
	],
	'category' => [
		'label'  => $this->lang('category'),
		'value'  => $this->form->value('category_id'),
		'values' => $this->form->value('categories'),
		'type'   => 'select',
		'rules'  => 'required'
	],
	'description' => [
		'label' => $this->lang('description'),
		'value' => $this->form->value('description'),
		'type'  => 'text'
	],
	'url' => [
		'label' => $this->lang('redirect'),
		'value' => $this->form->value('url'),
		'type'  => 'url'
	]
];
