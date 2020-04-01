<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'         => $this->lang('Titre de la page'),
		'value'         => $this->form()->value('title'),
		'type'          => 'text',
		'rules'         => 'required'
	],
	'subtitle' => [
		'label'         => $this->lang('Sous-titre'),
		'value'         => $this->form()->value('subtitle'),
		'type'          => 'text'
	],
	'name' => [
		'label'         => $this->lang('Chemin d\'accès'),
		'value'         => $name = $this->form()->value('name'),
		'type'          => 'text',
		'check'         => function($value, $post) use ($name){
			if (!$value)
			{
				$value = $post['title'];
			}

			$value = url_title($value);

			if ($value != $name && !NeoFrag()->db->from('nf_pages')->where('name', $value)->empty())
			{
				return $this->lang('Chemin d\'accès déjà utilisé');
			}
		}
	],
	'content' => [
		'label'			=> $this->lang('Contenu'),
		'value'			=> $this->form()->value('content'),
		'type'			=> 'editor'
	],
	'published' => [
		'type'			=> 'checkbox',
		'checked'		=> ['on' => $this->form()->value('published')],
		'values'        => ['on' => $this->lang('Publier la page dès maintenant')]
	]
];
