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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

$rules = array(
	'title' => array(
		'label'         => '{lang title}',
		'value'         => $title,
		'type'          => 'text',
		'rules'			=> 'required'
	),
	'category' => array(
		'label'         => '{lang category}',
		'value'         => $category_id,
		'values'        => $categories,
		'type'          => 'select',
		'rules'			=> 'required'
	),
	'image' => array(
		'label'       => '{lang image}',
		'value'       => $image_id,
		'type'        => 'file',
		'upload'      => 'news',
		'info'        => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return i18n('select_image_file');
			}
		}
	),
	'introduction' => array(
		'label'			=> '{lang intro}',
		'value'			=> $introduction,
		'type'			=> 'editor',
		'rules'			=> 'required'
	),
	'content' => array(
		'label'			=> '{lang content}',
		'value'			=> $content,
		'type'			=> 'editor'
	),
	'tags' => array(
		'label'			=> '{lang tags}',
		'value'			=> $tags,
		'type'			=> 'text'
	),
	'published' => array(
		'type'			=> 'checkbox',
		'checked'		=> array('on' => $published),
		'values'        => array('on' => '{lang published}')
	)
);

/*
NeoFrag Alpha 0.1
./modules/news/forms/news.php
*/