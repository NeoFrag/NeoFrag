<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'username' => [
		'label' => '{lang username}',
		'value' => $username = $this->form->value('username'),
		'rules' => 'required',
		'check' => function($value) use ($username){
			if ($value != $username && NeoFrag()->db->select('1')->from('nf_users')->where('username', $value)->row())
			{
				return $this->lang('username_unavailable');
			}
		}
	]
];

if (!NeoFrag()->url->admin)
{
	$rules = array_merge($rules, [
		'password_old' => [
			'label' => '{lang current_password}',
			'icon'  => 'fa-lock',
			'type'  => 'password',
			'check' => function($value, $post){
				if (strlen($value) && strlen($post['password_new']) && strlen($post['password_confirm']) && !NeoFrag()->password->is_valid($value.($salt = NeoFrag()->user('salt')), NeoFrag()->user('password'), (bool)$salt))
				{
					return $this->lang('invalid_password');
				}
			}
		],
		'password_new' => [
			'label' => '{lang new_password}',
			'icon'  => 'fa-lock',
			'type'  => 'password'
		],
		'password_confirm' => [
			'label' => '{lang password_confirmation}',
			'icon'  => 'fa-lock',
			'type'  => 'password',
			'check' => function($value, $post){
				if ($post['password_new'] != $value)
				{
					return '{password_not_match}';
				}
			}
		]
	]);
}

$rules = array_merge($rules, [
	'email' => [
		'label' => '{lang email}',
		'value' => $email = $this->form->value('email'),
		'type'  => 'email',
		'rules' => 'required',
		'check' => function($value) use ($email){
			if ($value != $email && NeoFrag()->db->select('1')->from('nf_users')->where('email', $value)->row())
			{
				return $this->lang('email_unavailable');
			}
		}
	],
	'first_name' => [
		'label' => '{lang first_name}',
		'value' => $this->form->value('first_name'),
	],
	'last_name' => [
		'label' => '{lang last_name}',
		'value' => $this->form->value('last_name'),
	],
	'avatar' => [
		'label'       => '{lang avatar}',
		'value'       => $this->form->value('avatar'),
		'upload'      => 'members',
		'type'        => 'file',
		'info'        => $this->lang('file_icon', 250, file_upload_max_size() / 1024 / 1024),
		'check'       => function($filename, $ext){
			if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
			{
				return $this->lang('select_image_file');
			}
			
			list($w, $h) = getimagesize($filename);
			
			if ($w != $h)
			{
				return $this->lang('avatar_must_be_square');
			}
			else if ($w < 250)
			{
				return $this->lang('avatar_size_error', 250);
			}
		},
		'post_upload' => function($filename){
			image_resize($filename, 250, 250);
		}
	],
	'date_of_birth' => [
		'label' => '{lang birth_date}',
		'value' => $this->form->value('date_of_birth'),
		'type'  => 'date',
		'check' => function($value){
			if ($value && strtotime($value) > strtotime(date('Y-m-d')))
			{
				return $this->lang('invalid_birth_date');
			}
		}
	],
	'sex' => [
		'label'  => '{lang gender}',
		'value'  => $this->form->value('sex'),
		'values' => [
			'female' => icon('fa-female').' {lang female}',
			'male'   => icon('fa-male').' {lang male}'
		],
		'type'   => 'radio'
	],
	'location' => [
		'label' => '{lang location}',
		'value' => $this->form->value('location')
	],
	'website' => [
		'label' => '{lang website}',
		'value' => $this->form->value('website'),
		'type'  => 'url'
	],
	'quote' => [
		'label'  => '{lang quote}',
		'value' => $this->form->value('quote')
	],
	'signature' => [
		'label' => '{lang signature}',
		'value' => $this->form->value('signature'),
		'type'  => 'editor'
	]
]);
