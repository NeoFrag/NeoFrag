<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label'         => $this->lang('Titre'),
		'value'         => $this->form()->value('title'),
		'type'          => 'text',
		'rules'			=> 'required'
	],
	'image' => [
		'label'       => $this->lang('Image'),
		'value'       => $this->form()->value('image'),
		'upload'      => 'news/categories',
		'type'        => 'file',
		'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}
		}
	],
	'icon' => [
		'label'       => $this->lang('Icône'),
		'value'       => $this->form()->value('icon'),
		'upload'      => 'news/categories',
		'type'        => 'file',
		'info'        => $this->lang(' d\'image (format carré min. %dpx et max. %d Mo)', 16, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('Veuiller choisir un fichier d\'image');
			}

			list($w, $h) = getimagesize($filename);

			if ($w != $h)
			{
				return $this->lang('L\'icône doit être carré');
			}
			else if ($w < 16)
			{
				return $this->lang('L\'icône doit faire au moins %dpx', 16);
			}
		}
	]
];
