<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'       => $this->lang('Nom'),
		'value'       => $this->form()->value('title'),
		'type'        => 'text',
		'rules'       => 'required',
		'size'        => 'col-6'
	],
	'logo_light'      => [
		'label'       => $this->lang('Logo clair'),
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
		'description' => $this->lang('Pour être affiché sur un fond foncé <i>(suivant le thème utilisé)</i>')
	],
	'logo_dark' => [
		'label'       => $this->lang('Logo foncé'),
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
		'description' => $this->lang('Pour être affiché sur un fond clair <i>(suivant le thème utilisé)</i>')
	],
	'description' => [
		'label'       => $this->lang('Présentation'),
		'value'       => $this->form()->value('description'),
		'type'        => 'editor'
	],
	'website' => [
		'label'       => $this->lang('Site internet'),
		'icon'        => 'fa-globe',
		'value'       => $this->form()->value('website'),
		'type'        => 'url',
		'rules'       => $this->lang('required'),
		'size'        => 'col-5'
	],
	'facebook' => [
		'label'       => $this->lang('Page Facebook'),
		'icon'        => 'fa-facebook',
		'value'       => $this->form()->value('facebook'),
		'type'        => 'url',
		'size'        => 'col-5'
	],
	'twitter' => [
		'label'       => $this->lang('Page Twitter'),
		'icon'        => 'fa-twitter',
		'value'       => $this->form()->value('twitter'),
		'type'        => 'url',
		'size'        => 'col-5'
	],
	'code' => [
		'label'       => $this->lang('Code promotionnel'),
		'icon'        => 'fa-gift',
		'value'       => $this->form()->value('code'),
		'type'        => 'text',
		'description' => $this->lang('Indiquez le code promotionnel que vos utilisateurs peuvent utiliser pour profiter de promotions grâce à votre partenaire'),
		'size'        => 'col-3'
	]
];
