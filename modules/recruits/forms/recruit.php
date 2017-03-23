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
		'label'       => 'Intitulé de l\'offre',
		'value'       => $this->form->value('title'),
		'type'        => 'text',
		'rules'       => 'required'
	],
	'team_id' => [
		'label'       => 'Associer à l\'équipe',
		'value'       => $this->form->value('team_id'),
		'values'      => $this->form->value('teams'),
		'type'        => 'select',
		'size'        => 'col-md-4',
		'description' => 'Laisser vide pour ne pas associer d\'équipe.<br />Si une candidature est acceptée, le joueur sera automatiquement ajoutée dans l\'équipe sélectionnée avec le rôle associé'
	],
	'role' => [
		'label'       => 'Rôle proposé',
		'value'       => $this->form->value('role'),
		'type'        => 'text',
		'icon'        => 'fa-sitemap',
		'description' => 'Exemple: Joueurs, Manager, etc...',
		'size'        => 'col-md-4',
		'rules'       => 'required'
	],
	'icon' => [
		'label'       => 'Icône',
		'value'       => $this->form->value('icon'),
		'default'     => 'fa-bullhorn',
		'type'        => 'iconpicker'
	],
	'size' => [
		'label'       => 'Nombre de place',
		'value'       => $this->form->value('size') ?: '1',
		'type'        => 'number',
		'size'        => 'col-md-2',
		'rules'       => 'required'
	],
	'date_end' => [
		'label'       => 'Date de clôture',
		'value'       => ($this->form->value('date_end') != '0000-00-00') ? $this->form->value('date_end') : '',
		'type'        => 'date',
		'check'       => function($value){
			if ($value && strtotime($value) < strtotime(date('Y-m-d')))
			{
				return 'Vraiment ?! 2.1 Gigowatt !';
			}
		},
		'size'        => 'col-md-4',
		'description' => 'Laisser vide pour créer une offre permanente'
	],
	'image' => [
		'label'       => 'Image',
		'value'       => $this->form->value('image_id'),
		'type'        => 'file',
		'upload'      => 'news',
		'info'        => ' d\'image (max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return 'Veuiller choisir un fichier d\'image';
			}
		}
	],
	'introduction' => [
		'label'       => 'Introduction',
		'value'       => $this->form->value('introduction'),
		'type'        => 'editor',
		'rules'       => 'required'
	],
	'description' => [
		'label'       => 'Description du poste',
		'value'       => $this->form->value('description'),
		'type'        => 'editor'
	],
	'requierments' => [
		'label'       => 'Profil recherché',
		'value'       => $this->form->value('requierments'),
		'type'        => 'editor'
	],
	'closed' => [
		'type'        => 'checkbox',
		'checked'     => ['on' => $this->form->value('closed')],
		'values'      => ['on' => 'Fermer le dépôt des candidatures']
	]
];

/*
NeoFrag Alpha 0.1.6
./modules/recruits/forms/recruit.php
*/