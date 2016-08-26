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
		'label'         => '{lang page_title}',
		'value'         => $title,
		'type'          => 'text',
		'rules'         => 'required'
	],
	'subtitle' => [
		'label'         => '{lang subtitle}',
		'value'         => $subtitle,
		'type'          => 'text'
	],
	'name' => [
		'label'         => '{lang access_path}',
		'value'         => $name,
		'type'          => 'text',
		'check'         => function($value, $post) use ($name){
			if (!$value)
			{
				$value = $post['title'];
			}
			
			$value = url_title($value);
			
			if ($value != $name && NeoFrag::loader()->db->select('1')->from('nf_pages')->where('name', $value)->row())
			{
				return i18n('access_path_already_used');
			}
		}
	],
	'content' => [
		'label'			=> '{lang content}',
		'value'			=> $content,
		'type'			=> 'editor'
	],
	'published' => [
		'type'			=> 'checkbox',
		'checked'		=> ['on' => $published],
		'values'        => ['on' => '{lang publish_now}']
	]
];

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/pages/forms/pages.php
*/