<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'       => 'Nom',
		'value'       => $this->form()->value('title'),
		'type'        => 'text',
		'rules'       => 'required',
		'size'        => 'col-6'
	],
	'logo_light'      => [
		'label'       => 'Logo clair',
		'value'       => $this->form()->value('logo_light'),
		'type'        => 'file',
		'upload'      => 'partners',
		'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		},
		'description' => 'Pour être affiché sur un fond foncé <i>(suivant le thème utilisé)</i>'
	],
	'logo_dark' => [
		'label'       => 'Logo foncé',
		'value'       => $this->form()->value('logo_dark'),
		'type'        => 'file',
		'upload'      => 'partners',
		'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		},
		'description' => 'Pour être affiché sur un fond clair <i>(suivant le thème utilisé)</i>'
	],
	'description' => [
		'label'       => 'Présentation',
		'value'       => $this->form()->value('description'),
		'type'        => 'editor'
	],
	'website' => [
		'label'       => 'Site internet',
		'icon'        => 'fas fa-globe',
		'value'       => $this->form()->value('website'),
		'type'        => 'url',
		'rules'       => 'required',
		'size'        => 'col-5'
	],
	'facebook' => [
		'label'       => 'Page Facebook',
		'icon'        => 'fab fa-facebook-f',
		'value'       => $this->form()->value('facebook'),
		'type'        => 'url',
		'size'        => 'col-5'
	],
	'twitter' => [
		'label'       => 'Page Twitter',
		'icon'        => 'fab fa-twitter',
		'value'       => $this->form()->value('twitter'),
		'type'        => 'url',
		'size'        => 'col-5'
	],
	'code' => [
		'label'       => 'Code promotionnel',
		'icon'        => 'fas fa-gift',
		'value'       => $this->form()->value('code'),
		'type'        => 'text',
		'description' => 'Indiquez le code promotionnel que vos utilisateurs peuvent utiliser pour profiter de promotions grâce à votre partenaire',
		'size'        => 'col-3'
	]
];
