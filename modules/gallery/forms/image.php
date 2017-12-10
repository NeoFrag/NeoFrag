<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => $this->lang('title'),
		'type'  => 'text',
		'value' => $this->form->value('title'),
		'rules' => 'required'
	],
	'description' => [
		'label' => $this->lang('description'),
		'type'  => 'textarea',
		'value' => $this->form->value('description')
	]
];
