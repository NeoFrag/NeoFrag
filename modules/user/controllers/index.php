<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		if (!$this->user())
		{
			return $this->login();
		}

		$this	->title($this->lang('member_area'))
				->js('user')
				->css('jquery.mCustomScrollbar.min')
				->js('jquery.mCustomScrollbar.min');

		return $this->row(
			$this->col(
				$this->_panel_profile($user_profile),
				$this->panel()->body($this->view('menu'), FALSE)
			),
			$this->col(
				$this->_panel_infos(),
				$this->panel()->body($this->view('index', $user_profile)),
				$this->_panel_activities()
			)
		);
	}

	public function edit()
	{
		$this	->title($this->lang('manage_my_account'))
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
			->add_submit($this->lang('save'))
			->add_back('user');

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

			redirect_back('user/'.$this->user('user_id').'/'.url_title($this->user('username')));
		}

		return [
			$this->row(
				$this->col(
					$this->_panel_profile(),
					$this->panel()->body($this->view('menu'), FALSE)
				),
				$this->col(
					$this	->panel()
							->heading()
							->body($this->form->display())
							->size('col-md-8 col-lg-9')
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
						return $data['remember_me'] ? '<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="'.$this->lang('persistent_connection').'"></i>' : '<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="'.$this->lang('nonpersistent_connection').'"></i>';
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
					'title'   => $this->lang('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('reference'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('unknown');
					}
				],
				[
					'title'   => $this->lang('initial_session_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this->lang('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				],
				[
					'content' => [function($data){
						if ($data['session_id'] != NeoFrag()->session('session_id'))
						{
							return $this->button_delete('user/sessions/delete/'.$data['session_id']);
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
					'title'   => $this->lang('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('reference'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('unknown');
					}
				],
				[
					'title'   => $this->lang('initial_session_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				]
			])
			->data($sessions)
			->no_data($this->lang('no_historic_available'));

		return [
			$this->row(
				$this->col(
					$this->_panel_profile(),
					$this->panel()->body($this->view('menu'), FALSE)
				),
				$this->col(
					$this	->panel()
							->heading($this->lang('my_active_sessions'), 'fa-shield')
							->body($active_sessions->display())
							->size('col-md-8 col-lg-9'),
					$this	->panel()
							->heading($this->lang('sessions_historic'), 'fa-power-off')
							->body($sessions_history->display())
				)
			)
		];
	}

	public function _session_delete($session_id)
	{
		$this	->title($this->lang('delete_confirmation'))
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('session_delete_message'));

		if ($this->form->is_valid())
		{
			$this->db	->where('session_id', $session_id)
						->delete('nf_sessions');

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _auth($authenticator)
	{
		try
		{
			spl_autoload_register(function($name){
				if (preg_match('/^SocialConnect/', $name))
				{
					require_once 'lib/'.str_replace('\\', '/', $name).'.php';
				}
			});

			$service = new \SocialConnect\Auth\Service(
				new \SocialConnect\Common\Http\Client\Curl(),
				new \SocialConnect\Provider\Session\NeoFrag($this->session), [
					'redirectUri' => $this->url->host.url('user/auth'),
					'provider'    => [
						$name = str_replace('_', '-', $authenticator->name) => $authenticator->config()
					]
				]
			);

			$provider = $service->getProvider($name);

			if ($callback = $authenticator->data($params))
			{
				$data = $callback($provider->getIdentity($provider->getAccessTokenByRequestParameters($params)));

				if (!($user_id = $this->db->select('user_id')->from('nf_users_auth')->where('authenticator', $authenticator->name)->where('id', $data['id'])->row()))
				{
					if ($data['avatar'])
					{
						$this	->network($data['avatar'])
								->stream($file = $this->file->filename('members', 'tmp'));

						$name = pathinfo($data['avatar'], PATHINFO_BASENAME);

						$data['avatar'] = 0;

						if (file_exists($file))
						{
							if ((list($w, $h, $type) = getimagesize($file)) && $w == $h && $w >= 250 && in_array($type, array_keys($extensions = [IMAGETYPE_GIF => 'gif', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png'])))
							{
								rename($file, $file = str_replace('.tmp', '.'.$extensions[$type], $file));
								image_resize($file, 250, 250);
								$data['avatar'] = $this->file->add($file, $name);
							}
							else
							{
								unlink($file);
							}
						}
					}
					else
					{
						$data['avatar'] = 0;
					}

					while ($data['username'] && $this->db->select('1')->from('nf_users')->where('username', $data['username'])->row())
					{
						$data['username'] = 'guest'.time();
					}

					if ($data['language'] && !$this->db->select('1')->from('nf_settings_languages')->where('code', $data['language'])->row())
					{
						$data['language'] = '';
					}

					if ($data['email'] && (!is_valid_email($data['email']) || $this->db->select('1')->from('nf_users')->where('email', $data['email'])->row()))
					{
						$data['email'] = '';
					}

					if ($data['website'] && !is_valid_url($data['website']))
					{
						$data['website'] = '';
					}

					$user_id = $this->db->insert('nf_users', [
						'username' => utf8_htmlentities($data['username']) ?: NULL,
						'password' => '',
						'salt'     => '',
						'email'    => $data['email']    ?: NULL,
						'language' => $data['language'] ?: NULL
					]);

					$this->db->insert('nf_users_auth', [
						'user_id'       => $user_id,
						'authenticator' => $authenticator->name,
						'id'            => $data['id']
					]);

					if (!in_array($data['sex'], ['female', 'male']))
					{
						$data['sex'] = NULL;
					}

					if ($data['first_name'] || $data['last_name'] || $data['avatar'] || $data['signature'] || $data['date_of_birth'] || $data['sex'] ||  $data['location'] ||  $data['website'])
					{
						$this->db->insert('nf_users_profiles', [
							'user_id'       => $user_id,
							'first_name'    => utf8_htmlentities($data['first_name']) ?: '',
							'last_name'     => utf8_htmlentities($data['last_name'])  ?: '',
							'avatar'        => $data['avatar']                        ?: NULL,
							'signature'     => utf8_htmlentities($data['signature'])  ?: '',
							'date_of_birth' => $data['date_of_birth']                 ?: NULL,
							'sex'           => $data['sex'],
							'location'      => utf8_htmlentities($data['location'])   ?: '',
							'website'       => $data['website']                       ?: ''
						]);
					}
				}

				$this->session->set('session', 'authenticator', $authenticator->name);
				$this->user->login($user_id, TRUE);
			}
			else
			{
				header('Location: '.$provider->makeAuthUrl());
				exit;
			}
		}
		catch (Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_WARNING);
		}

		redirect('user');
	}

	public function login($error = 0)
	{
		$this->title($this->lang('login'));

		$form_login = $this	->form
							->set_id('6e0fbe194d97aa8c83e9f9e6b5d07c66')
							->add_rules([
								'login' => [
									'label'       => $this->lang('username'),
									'description' => $this->lang('username_description'),
									'type'        => 'text',
									'rules'       => 'required|max(50)'
								],
								'password' => [
									'label' => $this->lang('password'),
									'type'  => 'password'
								],
								'remember_me' => [
									'type'   => 'checkbox',
									'values' => ['on' => $this->lang('remember_me')]
								],
								'redirect' => [
								]
							])
							->add_submit($this->lang('login'))
							->display_required(FALSE)
							->save();

		$rules = [
			'username' => [
				'label' => $this->lang('username'),
				'icon'  => 'fa-user',
				'rules' => 'required',
				'check' => function($value){
					if (NeoFrag()->db->select('1')->from('nf_users')->where('username', $value)->row())
					{
						return $this->lang('username_unavailable');
					}
				}
			],
			'password' => [
				'label' => $this->lang('password'),
				'icon'  => 'fa-lock',
				'type'  => 'password',
				'rules' => 'required'
			],
			'password_confirm' => [
				'label' => $this->lang('password_confirmation'),
				'icon'  => 'fa-lock',
				'type'  => 'password',
				'rules' => 'required',
				'check' => function($value, $post){
					if ($post['password'] != $value)
					{
						return $this->lang('password_not_match');
					}
				}
			],
			'email' => [
				'label' => $this->lang('email'),
				'type'  => 'email',
				'rules' => 'required',
				'check' => function($value){
					if (NeoFrag()->db->select('1')->from('nf_users')->where('email', $value)->row())
					{
						return $this->lang('email_unavailable');
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
			->add_submit($this->lang('create_account'))
			->fast_mode()
			->save();

		$rows = [];

		if (in_array($error, [NeoFrag::UNCONNECTED, NeoFrag::UNAUTHORIZED]))
		{
			header('HTTP/1.0 401 Unauthorized');

			$rows[] = $this->row(
				$this->col(
					$this	->panel()
							->heading($this->lang('login_required'), 'fa-warning')
							->body($this->lang('login_required_message'))
							->color('danger')
				)
			);
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
				//$form_login->alert('Compte utilisateur inactif !', 'Ce compte n\'a pas encore été activé par mail. Si vous n\'avez pas reçu de mail d\'activation vous pouvez utiliser la fonction <a href="'.url('user/activate').'" onclick="$(\'#form_activate\').submit(); return false;">Activation de compte</a>.', 'error');
			}
			else if ($user_id > 0 && $this->password->is_valid($password.$salt, $hash, (bool)$salt))
			{
				if (!$salt)
				{
					$this->db	->where('user_id', $user_id)
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
				$rows[] = $this->row(
					$this->col(
						$this	->panel()
								->heading($this->lang('invalid_login'), 'fa-warning')
								->body($this->lang('invalid_login_message'))
								->color('danger')
					)
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

		$rows[] = $this->row(
			$col = $this->col(
						$this	->panel()
								->heading($this->lang('login_title'), 'fa-sign-in')
								->body($this->view('login', [
									'form_id' => $form_login->token()
								]))
					)
					->size('col-md-6'),
			$this	->col(
						$this	->panel()
								->heading($this->lang('create_account_title'), 'fa-sign-in fa-rotate-90')
								->body($this->config->nf_registration_status == 0 ? $this->lang('create_account_message').$form_registration->display().($this->config->nf_registration_charte ? $this->view('charte') : '') : '<div class="alert alert-warning no-margin">Les inscriptions sur notre site sont fermées...</div>')
					)
					->size('col-md-6')
		);

		if ($authenticators = $this->addons->get_authenticators())
		{
			$this->css('auth');

			$col->prepend(
				$this	->panel()
						->heading('Connexion rapide', 'fa-user-circle')
						->body('<div class="text-center">'.implode($authenticators).'</div>')
			);
		}

		return $rows;
	}

	public function lost_password()
	{
		$this->title($this->lang('forgot_password'));

		$this	->form
				->add_rules([
					'email' => [
						'label' => $this->lang('email'),
						'type'  => 'email',
						'rules' => 'required',
						'check' => function($value){
							if (!NeoFrag()->db->select('1')->from('nf_users')->where('email', $value)->row())
							{
								return $this->lang('email_not_found');
							}
						}
					]
				])
				->add_submit($this->lang('save'))
				->add_back('user')
				->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->email
				->to($post['email'])
				->subject($this->lang('forgot_password'))
				->message('default', [
					'content' => function($data){
						return '<a href="'.url('user/lost-password/'.$data['key']).'">'.$this->lang('password_reset').'</a>';
					},
					'key'     => $this->model()->add_key($this->db->select('user_id')->from('nf_users')->where('email', $post['email'])->row())
				])
				->send();

			redirect_back('user');
		}

		return $this->panel()
					->heading($this->lang('forgot_password'), 'fa-unlock-alt')
					->body($this->form->display());
	}

	public function _lost_password($key_id, $user_id)
	{
		$this->title($this->lang('password_reset'));

		$this	->form
				->add_rules([
					'password' => [
						'label' => $this->lang('new_password'),
						'icon'  => 'fa-lock',
						'type'  => 'password',
						'rules' => 'required'
					],
					'password_confirm' => [
						'label' => $this->lang('password_confirmation'),
						'icon'  => 'fa-lock',
						'type'  => 'password',
						'rules' => 'required',
						'check' => function($value, $post){
							if ($post['password'] != $value)
							{
								return $this->lang('password_not_match');
							}
						}
					]
				])
				->add_submit($this->lang('save'))
				->add_back('user')
				->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->email
				->to($this->db->select('email')->from('nf_users')->where('user_id', $user_id)->row())
				->subject($this->lang('password_reset_confirmation_email'))
				->message('default', [
					'content' => $this->lang('password_reset_confirmation_message')
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

			redirect_back('user');
		}

		return $this->panel()
					->heading($this->lang('password_reset'), 'fa-lock')
					->body($this->form->display());
	}

	public function logout()
	{
		$this->user->logout();

		if ($this->config->nf_http_authentication)
		{
			$this->ajax();
			echo $this->lang('not_logged_in');
		}
		else
		{
			redirect();
		}
	}

	public function _messages($messages, $allow_delete = FALSE)
	{
		$this->breadcrumb();

		return $this->row(
			$this->col(
				$this->_panel_messages()
			),
			$this->col(
				$this	->panel()
						->heading()
						->body(!$messages ? '<h4 class="text-center">Aucun message</h4>' : $this->view('messages/inbox', [
							'messages'     => $messages,
							'allow_delete' => $allow_delete
						]), FALSE)
						->size('col-md-8 col-lg-9'),
				$this->panel_pagination()
			)
		);
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
					]
				])
				->add_submit('Envoyer');

		if ($this->form->is_valid($post))
		{
			$this->model('messages')->reply($message_id, $post['message']);

			redirect('user/messages/'.$message_id.'/'.url_title($title));
		}

		return [
			$this->row(
				$this->col(
					$this->_panel_messages()
				),
				$this->col(
					$this	->panel()
							->heading($title, 'fa-envelope-o')
							->body($this->view('messages/replies', [
								'replies' => $replies
							]))
							->size('col-md-8 col-lg-9'),
					$this	->panel()
							->heading('Répondre', 'fa-reply')
							->body($this->form->display())
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
				redirect('user/messages/'.$message_id.'/'.url_title($post['title']));
			}
		}

		return [
			$this->row(
				$this->col(
					$this->_panel_messages()
				),
				$this->col(
					$this	->panel()
							->heading()
							->body($this->form->display())
							->size('col-md-8 col-lg-9')
				)
			)
		];
	}

	public function _messages_delete($message_id, $title)
	{
		$this	->title($this->lang('delete_message'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le message <b>'.$title.'</b> ?');

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

	public function _member($user_id, $username)
	{
		$this->title($username);

		return [
			$this	->panel()
					->heading($username, 'fa-user')
					->body($this->view('profile_public', $this->model()->get_user_profile($user_id))),
			$this->panel_back($this->module('members') ? 'members' : '')
		];
	}

	public function _panel_profile(&$user_profile = NULL)
	{
		$this->css('profile');

		return $this->panel()
					->heading('Mon profil', 'fa-user')
					->body($this->view('profile', $user_profile = $this->model()->get_user_profile($this->user('user_id'))))
					->size('col-md-4 col-lg-3');
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

		return $this->panel()
					->body($this->view('infos', $infos))
					->size('col-md-8 col-lg-9');
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
		if ($forum = $this->module('forum'))
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

		return $this->panel()
					->heading('Activité récente')
					->body($this->view('activity', [
						'user_activity' => $user_activity
					]));
	}

	private function _panel_messages()
	{
		return $this->panel()
					->heading('Messagerie privée', 'fa-envelope-o')
					->body($this->view('messages/menu'), FALSE)
					->footer('<a href="'.url('user').'">'.icon('fa-arrow-circle-o-left').' Retour sur mon espace</a>', 'left')
					->size('col-md-4 col-lg-3');
	}
}
