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
		'label'       => 'Nom',
		'value'       => $title,
		'type'        => 'text',
		'rules'       => 'required',
		'size'        => 'col-md-6'
	],
	'logo_light'      => [
		'label'       => 'Logo clair',
		'value'       => $logo_light,
		'type'        => 'file',
		'upload'      => 'partners',
		'info'        => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return i18n('select_image_file');
			}
		},
		'description' => 'Pour être affiché sur un fond foncé <i>(suivant le thème utilisé)</i>'
	],
	'logo_dark' => [
		'label'       => 'Logo foncé',
		'value'       => $logo_dark,
		'type'        => 'file',
		'upload'      => 'partners',
		'info'        => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return i18n('select_image_file');
			}
		},
		'description' => 'Pour être affiché sur un fond clair <i>(suivant le thème utilisé)</i>'
	],
	'description' => [
		'label'       => 'Présentation',
		'value'       => $description,
		'type'        => 'editor'
	],
	'website' => [
		'label'       => 'Site internet',
		'icon'        => 'fa-globe',
		'value'       => $website,
		'type'        => 'url',
		'rules'       => 'required',
		'size'        => 'col-md-5'
	],
	'facebook' => [
		'label'       => 'Page Facebook',
		'icon'        => 'fa-facebook',
		'value'       => $facebook,
		'type'        => 'url',
		'size'        => 'col-md-5'
	],
	'twitter' => [
		'label'       => 'Page Twitter',
		'icon'        => 'fa-twitter',
		'value'       => $twitter,
		'type'        => 'url',
		'size'        => 'col-md-5'
	],
	'code' => [
		'label'       => 'Code promotionnel',
		'icon'        => 'fa-gift',
		'value'       => $code,
		'type'        => 'text',
		'description' => 'Indiquez le code promotionnel que vos utilisateurs peuvent utiliser pour profiter de promotions grâce à votre partenaire',
		'size'        => 'col-md-3'
	],
];

/*
NeoFrag Alpha 0.1.4
./modules/partners/forms/partners.php
*/