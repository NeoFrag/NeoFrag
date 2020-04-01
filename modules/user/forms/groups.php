<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => $this->lang('Nom'),
		'value' => $this->form()->value('title'),
		'rules' => 'required'.($this->form()->value('auto') ? '|disabled' : '')
	],
	'color' => [
		'label' => $this->lang('Couleur'),
		'value' => $this->form()->value('color'),
		'type'  => 'colorpicker'
	],
	'icon' => [
		'label'   => $this->lang('Icône'),
		'value'   => $this->form()->value('icon'),
		'default' => 'fas fa-user',
		'type'    => 'iconpicker'
	],
	'hidden' => [
		'checked' => ['on' => $this->form()->value('hidden')],
		'values'  => ['on' => 'Groupe caché'],
		'type'    => 'checkbox'
	]
];
