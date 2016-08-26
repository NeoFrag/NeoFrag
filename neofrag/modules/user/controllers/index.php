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

class m_user_c_index extends Controller_Module
{
	public function index()
	{
		if (!$this->user())
		{
			return $this->login();
		}

		$this	->title($this('member_area'))
				->js('user')
				->css('jquery.mCustomScrollbar.min')
				->js('jquery.mCustomScrollbar.min');

		return [
			new Row(
				new Col(
					$this->_panel_profile($user_profile),
					new Panel([
						'content' => $this->load->view('menu'),
						'body'    => FALSE
					])
				),
				new Col(
					$this->_panel_infos(),
					new Panel([
						'content' => $this->load->view('index', $user_profile)
					]),
					$this->_panel_activities()
				)
			)
		];
	}

	public function edit()
	{
		$this	->title($this('manage_my_account'))
				->icon('fa-cogs')
				->breadcrumb();

		$this->form
			->add_rules('user', [
				'username'      => $this->user('username'),
				'email'         => $this->user('email'),
				'first_name'    => $this->user('first_name'),
				'last_name'     => $this->user('last_name'),
				'avatar'        => $this->user('avatar'),
				'signature'     => $this->user('signature'),
				'date_of_birth' => $this->user('date_of_birth'),
				'sex'           => $this->user('sex'),
				'location'      => $this->user('location'),
				'website'       => $this->user('website'),
				'quote'         => $this->user('quote')
			])
			->add_submit($this('save'))
			->add_back('user.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_user(	$post['username'],
										$post['email'],
										$post['first_name'],
										$post['last_name'],
										$post['avatar'],
										$post['date_of_birth'],
										$post['sex'],
										$post['location'],
										$post['website'],
										$post['quote'],
										$post['signature']);

			if ($post['password_new'] && $post['password_new'] != $post['password_old'])
			{
				$this->model()->update_password($post['password_new']);

				$this->db	->where('user_id', $this->user('user_id'))
							->where('session_id <>', $this->session->get_session_id())
							->delete('nf_sessions');
			}

			redirect_back('members/'.$this->user('user_id').'/'.url_title($this->user('username')).'.html');
		}

		return [
			new Row(
				new Col(
					$this->_panel_profile(),
					new Panel([
						'content' => $this->load->view('menu'),
						'body'    => FALSE
					])
				),
				new Col(
					new Panel([
						'title'   => $this->load->data['module_title'],
						'icon'    => $this->load->data['module_icon'],
						'content' => $this->form->display(),
						'size'    => 'col-md-8 col-lg-9'
					])
				)
			)
		];
	}

	public function sessions($sessions)
	{
		$this	->title('Gérer mes sessions')
				->icon('fa-globe')
				->breadcrumb();
		
		$active_sessions = $this->table
			->add_columns([
				[
					'content' => function($data){
						return $data['remember_me'] ? '<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="'.i18n('persistent_connection').'"></i>' : '<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="'.i18n('nonpersistent_connection').'"></i>';
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'title'   => $this('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this('reference'),
					'content' => function($data, $loader){
						return $data['referer'] ? urltolink($data['referer']) : $loader->lang('unknown');
					}
				],
				[
					'title'   => $this('initial_session_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				],
				[
					'content' => [function($data){
						if ($data['session_id'] != NeoFrag::loader()->session('session_id'))
						{
							return button_delete('user/sessions/delete/'.$data['session_id'].'.html');
						}
					}]
				]
			])
			->pagination(FALSE)
			->data($this->user->get_sessions())
			->save();
		
		$sessions_history = $this->table
			->add_columns([
				[
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'title'   => $this('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this('reference'),
					'content' => function($data, $loader){
						return $data['referer'] ? urltolink($data['referer']) : $loader->lang('unknown');
					}
				],
				[
					'title'   => $this('initial_session_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				]
			])
			->data($sessions)
			->no_data($this('no_historic_available'));
		
		return [
			new Row(
				new Col(
					$this->_panel_profile(),
					new Panel([
						'content' => $this->load->view('menu'),
						'body'    => FALSE
					])
				),
				new Col(
					new Panel([
						'title'   => $this('my_active_sessions'),
						'icon'    => 'fa-shield',
						'content' => $active_sessions->display(),
						'size'    => 'col-md-8 col-lg-9'
					]),
					new Panel([
						'title'   => $this('sessions_historic'),
						'icon'    => 'fa-power-off',
						'content' => $sessions_history->display()
					])
				)
			)
		];
	}
	
	public function _session_delete($session_id)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('session_delete_message'));

		if ($this->form->is_valid())
		{
			$this->db	->where('session_id', $session_id)
						->delete('nf_sessions');

			return 'OK';
		}

		echo $this->form->display();
	}

	public function login($error = 0)
	{
		$this->title($this('login'));

		$form_login = $this	->form
							->set_id('6e0fbe194d97aa8c83e9f9e6b5d07c66')
							->add_rules([
								'login' => [
									'label'       => $this('username'),
									'description' => $this('username_description'),
									'type'        => 'text',
									'rules'       => 'required|max(50)'
								],
								'password' => [
									'label' => $this('password'),
									'type'  => 'password'
								],
								'remember_me' => [
									'type'   => 'checkbox',
									'values' => ['on' => $this('remember_me')]
								],
								'redirect' => [
								]
							])
							->add_submit($this('login'))
							->display_required(FALSE)
							->save();

		$rules = [
			'username' => [
				'label' => $this('username'),
				'icon'  => 'fa-user',
				'rules' => 'required',
				'check' => function($value){
					if (NeoFrag::loader()->db->select('1')->from('nf_users')->where('username', $value)->row())
					{
						return i18n('username_unavailable');
					}
				}
			],
			'password' => [
				'label' => $this('password'),
				'icon'  => 'fa-lock',
				'type'  => 'password',
				'rules' => 'required'
			],
			'password_confirm' => [
				'label' => $this('password_confirmation'),
				'icon'  => 'fa-lock',
				'type'  => 'password',
				'rules' => 'required',
				'check' => function($value, $post){
					if ($post['password'] != $value)
					{
						return i18n('password_not_match');
					}
				}
			],
			'email' => [
				'label' => $this('email'),
				'type'  => 'email',
				'rules' => 'required',
				'check' => function($value){
					if (NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
					{
						return i18n('email_unavailable');
					}
				}
			]
		];

		if (!empty($this->config->nf_registration_charte))
		{
			$rules['registration_charte'] = [
				'type'   => 'checkbox',
				'values' => ['on' => 'En vous inscrivant, vous reconnaissez avoir pris connaissance de <a href="#modalCharte" role="dialog" data-toggle="modal" data-target="#modalCharte"">notre règlement</a> et de l\'accepter.'],
				'rules'  => 'required'
			];
		}

		$form_registration = $this
			->form
			->add_rules($rules)
			->add_captcha()
			->add_submit($this('create_account'))
			->fast_mode()
			->save();

		$rows = [];
		
		if (in_array($error, [NeoFrag::UNCONNECTED, NeoFrag::UNAUTHORIZED]))
		{
			header('HTTP/1.0 401 Unauthorized');
			
			$rows[] = new Row(new Col(
				new Panel([
					'title'   => $this('login_required'),
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger',
					'content' => $this('login_required_message')
				])
				, 'col-md-12'
			));
		}

		if ($form_login->is_valid($post))
		{
			$login       = $post['login'];
			$password    = $post['password'];
			$remember_me = in_array('on', $post['remember_me']);
		
			$user_id = $this->model()->check_login($login, $hash, $salt);

			if ($user_id == -1)
			{
				//TODO
				//$form_login->alert('Compte utilisateur inactif !', 'Ce compte n\'a pas encore été activé par mail. Si vous n\'avez pas reçu de mail d\'activation vous pouvez utiliser la fonction <a href="'.url('user/activate.html').'" onclick="$(\'#form_activate\').submit(); return false;">Activation de compte</a>.', 'error');
			}
			else if ($user_id > 0 && $this->password->is_valid($password.$salt, $hash, (bool)$salt))
			{
				if (!$salt)
				{
					$this->db	->where('user_id', user_id)
								->update('nf_users', [
									'password' => $this->password->encrypt($password.($salt = unique_id())),
									'salt'     => $salt
								]);
				}
				
				$this->user->login($user_id, $remember_me);

				if ($post['redirect'])
				{
					redirect($post['redirect']);
				}
				else
				{
					refresh();
				}
			}
			else
			{
				$rows[] = new Row(new Col(
					new Panel([
						'title'   => $this('invalid_login'),
						'icon'    => 'fa-warning',
						'style'   => 'panel-danger',
						'content' => $this('invalid_login_message')
						]
					, 'col-md-12'
				))
				);
			}
		}
		else if ($form_registration->is_valid($post) && $this->config->nf_registration_status == 0)
		{
			$user_id = $this->db->insert('nf_users', [
				'username' => $post['username'],
				'password' => $this->password->encrypt($post['password'].($salt = unique_id())),
				'salt'     => $salt,
				'email'    => $post['email']
			]);

			if ($this->config->nf_welcome && $this->config->nf_welcome_user_id && $this->config->nf_welcome_title && $this->config->nf_welcome_content)
			{
				$this->model('messages')->insert_message($post['username'], $this->config->nf_welcome_title, str_replace('[pseudo]', '@'.$post['username'], $this->config->nf_welcome_content), TRUE);
			}

			$this->user->login($user_id);

			refresh();
		}
		
		$rows[] = new Row(
			new Col(
				new Panel([
					'title'   => $this('login_title'),
					'icon'    => 'fa-sign-in',
					'content' => $this->load->view('login', [
						'form_id' => $form_login->id
					])
				])
				, 'col-md-6'
			),
			new Col(
				new Panel([
					'title'   => $this('create_account_title'),
					'icon'    => 'fa-sign-in fa-rotate-90',
					'content' => $this->config->nf_registration_status == 0 ? $this('create_account_message').$form_registration->display().($this->config->nf_registration_charte ? $this->load->view('charte') : '') : '<div class="alert alert-warning no-margin">Les inscriptions sur notre site sont fermées...</div>'
				])
				, 'col-md-6'
			)
		);
		
		return $rows;
	}

	public function lost_password()
	{
		$this->title($this('forgot_password'));
		
		$this	->form
				->add_rules([
					'email' => [
						'label' => $this('email'),
						'type'  => 'email',
						'rules' => 'required',
						'check' => function($value){
							if (!NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
							{
								return i18n('email_not_found');
							}
						}
					]
				])
				->add_submit($this('save'))
				->add_back('user.html')
				->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->email
				->to($post['email'])
				->subject($this('forgot_password'))
				->message('default', [
					'content' => function($data){
						return '<a href="'.url('user/lost-password/'.$data['key'].'.html').'">'.$this('password_reset').'</a>';
					},
					'key'     => $this->model()->add_key($this->db->select('user_id')->from('nf_users')->where('email', $post['email'])->row())
				])
				->send();
			
			redirect_back('user.html');
		}
					
		return new Panel([
				'title'   => $this('forgot_password'),
				'icon'    => 'fa-unlock-alt',
				'content' => $this->form->display()
			]);
	}
	
	public function _lost_password($key_id, $user_id)
	{
		$this->title($this('password_reset'));
	
		$this	->form
				->add_rules([
					'password' => [
						'label' => $this('new_password'),
						'icon'  => 'fa-lock',
						'type'  => 'password',
						'rules' => 'required'
					],
					'password_confirm' => [
						'label' => $this('password_confirmation'),
						'icon'  => 'fa-lock',
						'type'  => 'password',
						'rules' => 'required',
						'check' => function($value, $post){
							if ($post['password'] != $value)
							{
								return i18n('password_not_match');
							}
						}
					]
				])
				->add_submit($this('save'))
				->add_back('user.html')
				->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->email
				->to($this->db->select('email')->from('nf_users')->where('user_id', $user_id)->row())
				->subject($this('password_reset_confirmation_email'))
				->message('default', [
					'content' => $this('password_reset_confirmation_message')
				])
				->send();

			$this->user->login($user_id);

			$this->model()	->update_password($post['password'])
							->delete_key($key_id);

			foreach ($this->user->get_sessions() as $session)
			{
				if ($session['session_id'] != $this->session('session_id'))
				{
					//TODO ajouter une alerte pour ces sessions pour expliquer pk ils sont déco
					$this->session->disconnect($session['session_id']);
				}
			}
			
			redirect_back('user.html');
		}
					
		return new Panel([
				'title'   => $this('password_reset'),
				'icon'    => 'fa-lock',
				'content' => $this->form->display()
			]);
	}

	public function logout()
	{
		$this->user->logout();

		if ($this->config->nf_http_authentication)
		{
			$this->ajax();
			echo $this('not_logged_in');
		}
		else
		{
			redirect();
		}
	}

	public function _messages($messages, $allow_delete = FALSE)
	{
		$this->breadcrumb();

		return [
			new Row(
				new Col(
					$this->_panel_messages()
				),
				new Col(
					new Panel([
						'title'   => $this->load->data['module_title'],
						'icon'    => $this->load->data['module_icon'],
						'content' => !$messages ? '<h4 class="text-center">Aucun message</h4>' : $this->load->view('messages/inbox', [
							'messages'     => $messages,
							'allow_delete' => $allow_delete
						]),
						'body'    => FALSE,
						'size'    => 'col-md-8 col-lg-9'
					]),
					new Panel_Pagination
				)
			)
		];
	}

	public function _messages_inbox($messages)
	{
		return $this	->title('Boîte de réception')
						->icon('fa-inbox')
						->_messages($messages, TRUE);
	}

	public function _messages_sent($messages)
	{
		return $this	->title('Messages envoyés')
						->icon('fa-send-o')
						->_messages($messages);
	}

	public function _messages_archives($messages)
	{
		return $this	->title('Archives')
						->icon('fa-archive')
						->_messages($messages);
	}

	public function _messages_read($message_id, $title, $replies)
	{
		$this	->form
				->add_rules([
					'message' => [
						'label' => 'Mon message',
						'type'  => 'editor',
						'rules' => 'required'
					],
				])
				->add_submit('Envoyer');

		if ($this->form->is_valid($post))
		{
			$this->model('messages')->reply($message_id, $post['message']);

			redirect('user/messages/'.$message_id.'/'.url_title($title).'.html');
		}

		return [
			new Row(
				new Col(
					$this->_panel_messages()
				),
				new Col(
					new Panel([
						'title'   => $title,
						'icon'    => 'fa-envelope-o',
						'content' => $this->load->view('messages/replies', [
							'replies' => $replies
						]),
						'size'    => 'col-md-8 col-lg-9'
					]),
					new Panel([
						'title'   => 'Répondre',
						'icon'    => 'fa-reply',
						'content' => $this->form->display()
					])
				)
			)
		];
	}

	public function _messages_compose($username)
	{
		$this	->title('Nouveau message')
				->icon('fa-edit')
				->breadcrumb()
				->form
				->add_rules([
					'title' => [
						'label' => 'Sujet du message',
						'type'  => 'text',
						'rules' => 'required'
					],
					'recipients' => [
						'label'       => 'Destinataires',
						'value'       => $username,
						'type'        => 'text',
						'rules'       => 'required',
						'description' => 'Séparez plusieurs destinataires par un <b>;</b> <small>(point virgule)</small>'
					],
					'message' => [
						'label' => 'Mon message',
						'type'  => 'editor',
						'rules' => 'required'
					]
				])
				->add_submit('Envoyer');

		if ($this->form->is_valid($post))
		{
			if ($message_id = $this->model('messages')->insert_message($post['recipients'], $post['title'], $post['message']))
			{
				redirect('user/messages/'.$message_id.'/'.url_title($post['title']).'.html');
			}
		}
		
		return [
			new Row(
				new Col(
					$this->_panel_messages()
				),
				new Col(
					new Panel([
						'title'   => $this->load->data['module_title'],
						'icon'    => $this->load->data['module_icon'],
						'content' => $this->form->display(),
						'size'    => 'col-md-8 col-lg-9'
					])
				)
			)
		];
	}
	
	public function _messages_delete($message_id, $title)
	{
		$this	->title($this('delete_message'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le message <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->db	->where('user_id', $this->user('user_id'))
						->where('message_id', $message_id)
						->update('nf_users_messages_recipients', [
							'date'    => now(),
							'deleted' => TRUE
						]);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _panel_profile(&$user_profile = NULL)
	{
		$this->css('profile');

		return new Panel([
			'title'   => 'Mon profil',
			'icon'    => 'fa-user',
			'content' => $this->load->view('profile', $user_profile = $this->model()->get_member_profile($this->user('user_id'))),
			'size'    => 'col-md-4 col-lg-3'
		]);
	}
	
	public function _panel_infos($user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = $this->user('user_id');
			
			$infos = [
				'registration_date'  => $this->user('registration_date'),
				'last_activity_date' => $this->user('last_activity_date')
			];
		}
		else
		{
			$infos = $this->db	->select('registration_date', 'last_activity_date')
								->from('nf_users')
								->where('user_id', $user_id)
								->where('deleted', FALSE)
								->row();
		}
		
		$infos['groups'] = $this->groups->user_groups($user_id);

		return new Panel([
			'content' => $this->load->view('infos', $infos),
			'size'    => 'col-md-8 col-lg-9'
		]);
	}
	
	public function _panel_activities($user_id = NULL)
	{
		$this->css('activities');

		if ($user_id === NULL)
		{
			$user_id = $this->user('user_id');
		}

		$user_activity = [];

		//TODO
		if ($forum = $this->load->module('forum'))
		{
			$categories = array_filter($this->db->select('category_id')->from('nf_forum_categories')->get(), function($a){
				return $this->access('forum', 'category_read', $a);
			});

			$user_activity = $this->db	->select('m.message_id', 'm.topic_id', 't.title', 'u.user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'm.message', 'UNIX_TIMESTAMP(m.date) as date')
										->from('nf_forum_messages m')
										->join('nf_forum_topics   t',  'm.topic_id = t.topic_id')
										->join('nf_forum          f',  't.forum_id = f.forum_id')
										->join('nf_forum          f2', 'f.parent_id = f2.forum_id AND f.is_subforum = "1"')
										->join('nf_users          u',  'm.user_id = u.user_id AND u.deleted = "0"')
										->join('nf_users_profiles up', 'u.user_id = up.user_id')
										->where('m.user_id', $user_id)
										->where('IFNULL(f2.parent_id, f.parent_id)', $categories)
										->order_by('m.date DESC')
										->limit(10)
										->get();
		}

		return new Panel([
			'title'   => 'Activité récente',
			'content' => $this->load->view('activity', [
				'user_activity' => $user_activity
			])
		]);
	}
	
	private function _panel_messages()
	{
		return new Panel([
			'title'        => 'Messagerie privée',
			'icon'         => 'fa-envelope-o',
			'content'      => $this->load->view('messages/menu'),
			'body'         => FALSE,
			'footer'       => '<a href="'.url('user.html').'">'.icon('fa-arrow-circle-o-left').' Retour sur mon espace</a>',
			'footer_align' => 'left',
			'size'         => 'col-md-4 col-lg-3'
		]);
	}
}

/*
NeoFrag Alpha 0.1.4.2
./neofrag/modules/user/controllers/index.php
*/