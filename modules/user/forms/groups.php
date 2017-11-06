<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => '{lang name}',
		'value' => $this->form->value('title'),
		'rules' => 'required'.($this->form->value('auto') ? '|disabled' : '')
	],
	'color' => [
		'label' => '{lang color}',
		'value' => $this->form->value('color'),
		'type'  => 'colorpicker'
	],
	'icon' => [
		'label'   => '{lang icon}',
		'value'   => $this->form->value('icon'),
		'default' => 'fa-user',
		'type'    => 'iconpicker'
	],
	'hidden' => [
		'checked' => ['on' => $this->form->value('hidden')],
		'values'  => ['on' => 'Groupe caché'],
		'type'    => 'checkbox'
	]
];
