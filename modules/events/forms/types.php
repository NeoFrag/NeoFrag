<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'   => $this->lang('Titre'),
		'value'   => $this->form()->value('title'),
		'type'    => 'text',
		'rules'   => $this->lang('required')
	],
	'type' => [
		'label'   => $this->lang('Type'),
		'value'   => $this->form()->value('type') ?: 0,
		'values'  => $this->model('types')->get_types_list(),
		'type'    => 'radio',
		'rules'   => $this->lang('required')
	],
	'color' => [
		'label'   => $this->lang('Couleur'),
		'value'   => $this->form()->value('color'),
		'type'    => 'colorpicker'
	],
	'icon' => [
		'label'   => $this->lang('Icône'),
		'value'   => $this->form()->value('icon'),
		'default' => 'fa-clock-o',
		'type'    => 'iconpicker'
	]
];
