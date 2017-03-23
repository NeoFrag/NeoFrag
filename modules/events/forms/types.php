<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

$rules = [
	'title' => [
		'label'   => 'Titre',
		'value'   => $this->form->value('title'),
		'type'    => 'text',
		'rules'   => 'required'
	],
	'type' => [
		'label'   => 'Type',
		'value'   => $this->form->value('type') ?: 0,
		'values'  => $this->model('types')->get_types_list(),
		'type'    => 'radio',
		'rules'   => 'required'
	],
	'color' => [
		'label'   => 'Couleur',
		'value'   => $this->form->value('color'),
		'type'    => 'colorpicker'
	],
	'icon' => [
		'label'   => 'Icône',
		'value'   => $this->form->value('icon'),
		'default' => 'fa-clock-o',
		'type'    => 'iconpicker'
	]
];

/*
NeoFrag Alpha 0.1.6
./modules/events/forms/types.php
*/