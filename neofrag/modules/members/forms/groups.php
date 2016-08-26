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
		'label' => '{lang name}',
		'value' => $title,
		'rules' => 'required'.(!empty($auto) ? '|disabled' : '')
	],
	'color' => [
		'label' => '{lang color}',
		'value' => $color,
		'type'  => 'colorpicker'
	],
	'icon' => [
		'label'   => '{lang icon}',
		'value'   => $icon,
		'default' => 'fa-user',
		'type'    => 'iconpicker'
	]
];

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/members/forms/groups.php
*/