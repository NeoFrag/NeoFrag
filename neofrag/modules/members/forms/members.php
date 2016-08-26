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
	'username' => [
		'label' => '{lang username}',
		'value' => $username,
		'rules' => 'required',
		'check' => function($value) use ($username){
			if ($value != $username && NeoFrag::loader()->db->select('1')->from('nf_users')->where('username', $value)->row())
			{
				return i18n('username_unavailable');
			}
		}
	],
	'email' => [
		'label' => '{lang email}',
		'value' => $email,
		'type'  => 'email',
		'rules' => 'required',
		'check' => function($value) use ($email){
			if ($value != $email && NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
			{
				return i18n('email_unavailable');
			}
		}
	],
	'first_name' => [
		'label' => '{lang first_name}',
		'value' => $first_name,
	],
	'last_name' => [
		'label' => '{lang last_name}',
		'value' => $last_name,
	],
	'avatar' => [
		'label'       => '{lang avatar}',
		'value'       => $avatar,
		'upload'      => 'members',
		'type'        => 'file',
		'info'        => i18n('file_icon', 250, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return i18n('select_image_file');
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return i18n('avatar_must_be_square');
			}
			else if ($w < 250)
			{
				return i18n('avatar_size_error', 250);
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 250, 250);
		}
	],
	'date_of_birth' => [
		'label' => '{lang birth_date}',
		'value' => $date_of_birth,
		'type'  => 'date',
		'check' => function($value){
			if ($value && strtotime($value) > strtotime(date('Y-m-d')))
			{
				return i18n('invalid_birth_date');
			}
		}
	],
	'sex' => [
		'label'  => '{lang gender}',
		'value'  => $sex,
		'values' => [
			'female' => icon('fa-female').' {lang female}',
			'male'   => icon('fa-male').' {lang male}'
		],
		'type'   => 'radio'
	],
	'location' => [
		'label' => '{lang location}',
		'value' => $location
	],
	'website' => [
		'label' => '{lang website}',
		'value' => $website,
		'type'  => 'url'
	],
	'quote' => [
		'label'  => '{lang quote}',
		'value' => $quote
	],
	'signature' => [
		'label' => '{lang signature}',
		'value' => $signature,
		'type'  => 'editor'
	]
];

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/members/forms/members.php
*/