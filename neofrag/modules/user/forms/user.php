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

$rules = array(
	'username' => array(
		'label' => '{lang username}',
		'value' => $username,
		'rules' => 'required',
		'check' => function($value) use ($username){
			if ($value != $username && NeoFrag::loader()->db->select('1')->from('nf_users')->where('username', $value)->row())
			{
				return i18n('username_unavailable');
			}
		}
	),
	'password_old' => array(
		'label' => '{lang current_password}',
		'icon'  => 'fa-lock',
		'type'  => 'password',
		'check' => function($value, $post){
			if (strlen($value) && strlen($post['password_new']) && strlen($post['password_confirm']) && !NeoFrag::loader()->load->library('password')->is_valid($value.($salt = NeoFrag::loader()->user('salt')), NeoFrag::loader()->user('password'), (bool)$salt))
			{
				return i18n('invalid_password');
			}
		}
	),
	'password_new' => array(
		'label' => '{lang new_password}',
		'icon'  => 'fa-lock',
		'type'  => 'password'
	),
	'password_confirm' => array(
		'label' => '{lang password_confirmation}',
		'icon'  => 'fa-lock',
		'type'  => 'password',
		'check' => function($value, $post){
			if ($post['password_new'] != $value)
			{
				return '{password_not_match}';
			}
		}
	),
	'email' => array(
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
	),
	'first_name' => array(
		'label' => '{lang first_name}',
		'value' => $first_name,
	),
	'last_name' => array(
		'label' => '{lang last_name}',
		'value' => $last_name,
	),
	'avatar' => array(
		'label'       => '{lang avatar}',
		'value'       => $avatar,
		'upload'      => 'members',
		'type'        => 'file',
		'info'        => i18n('file_icon', 250, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
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
	),
	'date_of_birth' => array(
		'label' => '{lang birth_date}',
		'value' => $date_of_birth && $date_of_birth != '0000-00-00' ? timetostr(i18n('date_short'), strtotime($date_of_birth)) : '',
		'type'  => 'date',
		'check' => function($value){
			if ($value && strtotime($value) > strtotime(date('Y-m-d')))
			{
				return i18n('invalid_birth_date');
			}
		}
	),
	'sex' => array(
		'label'  => '{lang gender}',
		'value'  => $sex,
		'values' => array(
			'female' => icon('fa-female').' {lang female}',
			'male'   => icon('fa-male').' {lang male}'
		),
		'type'   => 'radio'
	),
	'location' => array(
		'label' => '{lang location}',
		'value' => $location
	),
	'website' => array(
		'label' => '{lang website}',
		'value' => $website,
		'type'  => 'url'
	),
	'quote' => array(
		'label'  => '{lang quote}',
		'value' => $quote
	),
	'signature' => array(
		'label' => '{lang signature}',
		'value' => $signature,
		'type'  => 'editor'
	)
);

/*
NeoFrag Alpha 0.1.2
./neofrag/modules/user/forms/user.php
*/