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
	'username' => array(
		'label' => 'Identifiant',
		'value' => $username,
		'rules' => 'required',
		'check' => function($value) use ($username){
			if ($value != $username && NeoFrag::loader()->db->select('1')->from('nf_users')->where('username', $value)->row())
			{
				return 'Identifiant déjà utilisé';
			}
		}
	),
	'email' => array(
		'label' => 'Adresse email',
		'value' => $email,
		'type'  => 'email',
		'rules' => 'required',
		'check' => function($value) use ($email){
			if ($value != $email && NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
			{
				return 'Addresse email déjà utilisée';
			}
		}
	),
	'first_name' => array(
		'label' => 'Prénom',
		'value' => $first_name,
	),
	'last_name' => array(
		'label' => 'Nom',
		'value' => $last_name,
	),
	'avatar' => array(
		'label'       => 'Avatar',
		'value'       => $avatar,
		'upload'      => 'members',
		'type'        => 'file',
		'info'        => ' d\'image (format carré min. 250px et max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
		'check'       => function($filename, $ext){
			if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
			{
				return 'Veuiller choisir un fichier d\'image';
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return 'L\'avatar doit être carré';
			}
			else if ($w < 250)
			{
				return 'L\'avatar doit faire au moins 250px';
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 250, 250);
		}
	),
	'date_of_birth' => array(
		'label' => 'Date de naissance',
		'value' => $date_of_birth && $date_of_birth != '0000-00-00' ? timetostr(NeoFrag::loader()->lang('date_short'), strtotime($date_of_birth)) : '',
		'type'  => 'date',
		'check' => function($value){
			if ($value && strtotime($value) > strtotime(date('Y-m-d')))
			{
				return 'Vraiment ?! 2.1 Gigowatt !';
			}
		}
	),
	'sex' => array(
		'label'  => 'Sexe',
		'value'  => $sex,
		'values' => array(
			'female' => icon('fa-female').' Femme',
			'male'   => icon('fa-male').' Homme'
		),
		'type'   => 'radio'
	),
	'location' => array(
		'label' => 'Localisation',
		'value' => $location
	),
	'website' => array(
		'label' => 'Site web',
		'value' => $website,
		'type'  => 'url'
	),
	'quote' => array(
		'label'  => 'Citation',
		'value' => $quote
	),
	'signature' => array(
		'label' => 'Signature',
		'value' => $signature,
		'type'  => 'editor'
	)
);

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/members/forms/members.php
*/