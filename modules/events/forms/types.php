<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'   => 'Titre',
		'value'   => $this->form()->value('title'),
		'type'    => 'text',
		'rules'   => 'required'
	],
	'type' => [
		'label'   => 'Type',
		'value'   => $this->form()->value('type') ?: 0,
		'values'  => $this->model('types')->get_types_list(),
		'type'    => 'radio',
		'rules'   => 'required'
	],
	'color' => [
		'label'   => 'Couleur',
		'value'   => $this->form()->value('color'),
		'type'    => 'colorpicker'
	],
	'icon' => [
		'label'   => 'Icône',
		'value'   => $this->form()->value('icon'),
		'default' => 'far fa-clock',
		'type'    => 'iconpicker'
	]
];
