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
		
		$this->title('Espace membre');
		
		return new Panel(array(
			'title'   => 'Espace membre',
			'icon'    => 'fa-user',
			'content' => $this->load->view('index'),
			'footer'  => '<a class="btn btn-primary" href="'.url('user/edit.html').'">'.icon('fa-cogs').' Gérer mon compte</a> <a class="btn btn-danger" href="'.url('user/logout.html').'">'.icon('fa-close').' Déconnexion</a>'
		));
	}

	public function edit()
	{
		$this->title('Gérer mon compte');
		
		$this->load->library('form')
			->add_rules('user', array(
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
			))
			->add_submit('Valider')
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
		
		return new Panel(array(
			'title'           => 'Gérer mon compte',
			'icon'            => 'fa-cogs',
			'content'         => $this->form->display()
		));
	}

	public function sessions($sessions)
	{
		$this->title('Sessions');
		
		$active_sessions = $this->load->library('table')
			->add_columns(array(
				array(
					'content' => function($data){
						return $data['remember_me'] ? '<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="Connexion persistante"></i>' : '<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="Connexion non persistante"></i>';
					},
					'size'    => TRUE,
					'align'   => 'center'
				),
				array(
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				),
				array(
					'title'   => 'Adresse IP',
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				),
				array(
					'title'   => 'Site référent',
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : 'Aucun';
					}
				),
				array(
					'title'   => 'Date d\'arrivée',
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				),
				array(
					'title'   => 'Dernière activité',
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				),
				array(
					'content' => array(function($data){
						if ($data['session_id'] != NeoFrag::loader()->session('session_id'))
						{
							return button_delete('user/sessions/delete/'.$data['session_id'].'.html');
						}
					})
				)
			))
			->pagination(FALSE)
			->data($this->user->get_sessions())
			->save();
		
		$sessions_history = $this->table
			->add_columns(array(
				array(
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				),
				array(
					'title'   => 'Adresse IP',
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				),
				array(
					'title'   => 'Site référent',
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : 'Aucun';
					}
				),
				array(
					'title'   => 'Date d\'arrivée',
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				)
			))
			->data($sessions)
			->no_data('Aucun historique disponible');
		
		return array(
			new Panel(array(
				'title'   => 'Mes sessions actives',
				'icon'    => 'fa-shield',
				'content' => $active_sessions->display()
			)),
			new Panel(array(
				'title'   => 'Historique de mes sessions',
				'icon'    => 'fa-power-off',
				'content' => $sessions_history->display()
			)),
			new Button_back('user.html')
		);
	}
	
	public function _session_delete($session_id)
	{
		$this	->title('Confirmation de suppression')
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer cette session ?');

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
		$this->title('Connexion');

		$form_login = $this
			->load
			->library('form')
			->set_id('6e0fbe194d97aa8c83e9f9e6b5d07c66')
			->add_rules(array(
				'login' => array(
					'label'       => 'Identifiant',
					'description' => 'Vous pouvez vous identifier avec votre adresse mail ou bien votre pseudonyme.',
					'type'        => 'text',
					'rules'       => 'required|max(50)'
				),
				'password' => array(
					'label' => 'Mot de passe',
					'type'  => 'password'
				),
				'remember_me' => array(
					'type'   => 'checkbox',
					'values' => array('on' => 'Se souvenir de moi')
				),
				'redirect' => array(
				)
			))
			->add_submit('Connexion')
			->display_required(FALSE)
			->save();
		
		$form_registration = $this
			->form
			->add_rules(array(
				'username' => array(
					'label' => 'Identifiant',
					'icon'  => 'fa-user',
					'rules' => 'required',
					'check' => function($value){
						if (NeoFrag::loader()->db->select('1')->from('nf_users')->where('username', $value)->row())
						{
							return 'Identifiant déjà utilisé';
						}
					}
				),
				'password' => array(
					'label' => 'Mot de passe',
					'icon'  => 'fa-lock',
					'type'  => 'password',
					'rules' => 'required'
				),
				'password_confirm' => array(
					'label' => 'Confirmation',
					'icon'  => 'fa-lock',
					'type'  => 'password',
					'rules' => 'required',
					'check' => function($value, $post){
						if ($post['password'] != $value)
						{
							return 'Les mots de passe doivent être identiques';
						}
					}
				),
				'email' => array(
					'label' => 'Adresse email',
					'type'  => 'email',
					'rules' => 'required',
					'check' => function($value){
						if (NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
						{
							return 'Addresse email déjà utilisée';
						}
					}
				)
			))
			->add_submit('Créer un compte')
			->fast_mode()
			->save();

		$rows = array();
		
		if (in_array($error, array(NeoFrag::UNCONNECTED, NeoFrag::UNAUTHORIZED)))
		{
			header('HTTP/1.0 401 Unauthorized');
			
			$rows[] = new Row(new Col(
				new Panel(array(
					'title'   => 'Connexion requise',
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger',
					'content' => '<p>La page que vous souhaitez consulter n\'est accessible qu\'aux utilisateurs connectés.</p>Connectez-vous si vous avez déjà un compte utilisateur.<br />Vous pouvez aussi créer un nouveau compte en vous inscrivant ci-dessous.'
				))
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
			else if ($user_id > 0 && $this->load->library('password')->is_valid($password.$salt, $hash, (bool)$salt))
			{
				if (!$salt)
				{
					$this->db	->where('user_id', user_id)
								->update('nf_users', array(
									'password' => $this->password->encrypt($password.($salt = unique_id())),
									'salt'     => $salt
								));
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
					new Panel(array(
						'title'   => 'Identifiants incorrects !',
						'icon'    => 'fa-warning',
						'style'   => 'panel-danger',
						'content' => 'Si vous avez oublié votre mot de passe, utilisez la fonction <a href="'.url('user/lost-password.html').'">Mot de passe oublié</a>, sinon vous pouvez créer un compte ci-dessous.'
					))
					, 'col-md-12'
				));
			}
		}
		else if ($form_registration->is_valid($post))
		{
			$this->user->login($this->db->insert('nf_users', array(
				'username' => $post['username'],
				'password' => $this->load->library('password')->encrypt($post['password'].($salt = unique_id())),
				'salt'     => $salt,
				'email'    => $post['email']
			)));

			refresh();
		}
		
		$rows[] = new Row(
			new Col(
				new Panel(array(
					'title'   => 'Se connecter',
					'icon'    => 'fa-sign-out',
					'content' => $this->load->view('login', array(
						'form_id' => $form_login->id
					))
				))
				, 'col-md-6'
			),
			new Col(
				new Panel(array(
					'title'   => 'Pas encore inscrit ?',
					'icon'    => 'fa-sign-in',
					'content' => '<p>Créez votre compte maintenant pour profiter pleinement du site.</p>'.$form_registration->display()
				))
				, 'col-md-6'
			)
		);
		
		return $rows;
	}

	public function lost_password()
	{
		$this->title('Mot de passe oublié ?');
		
		$this->load	->library('form')
					->add_rules(array(
						'email' => array(
							'label' => 'Adresse email',
							'type'  => 'email',
							'rules' => 'required',
							'check' => function($value){
								if (!NeoFrag::loader()->db->select('1')->from('nf_users')->where('email', $value)->row())
								{
									return 'Addresse email introuvable';
								}
							}
						)
					))
					->add_submit('Valider')
					->add_back('user.html')
					->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->load->library('email')
				->to($post['email'])
				->subject('Mot de passe oublié ?')
				->message('default', array(
					'content' => function($data){
						return '<a href="'.url('user/lost-password/'.$data['key'].'.html').'">Réinitialisation de votre mot de passe</a>';
					},
					'key'     => $this->model()->add_key($this->db->select('user_id')->from('nf_users')->where('email', $post['email'])->row())
				))
				->send();
			
			redirect_back('user.html');
		}
					
		return new Panel(array(
				'title'   => 'Mot de passe oublié ?',
				'icon'    => 'fa-unlock-alt',
				'content' => $this->form->display()
			));
	}
	
	public function _lost_password($key_id, $user_id)
	{
		$this->title('Réinitialisation de votre mot de passe');
		
		$this->load	->library('form')
					->add_rules(array(
						'password' => array(
							'label' => 'Nouveau mot de passe',
							'icon'  => 'fa-lock',
							'type'  => 'password',
							'rules' => 'required'
						),
						'password_confirm' => array(
							'label' => 'Confirmation',
							'icon'  => 'fa-lock',
							'type'  => 'password',
							'rules' => 'required',
							'check' => function($value, $post){
								if ($post['password'] != $value)
								{
									return 'Les mots de passe doivent être identiques';
								}
							}
						)
					))
					->add_submit('Valider')
					->add_back('user.html')
					->fast_mode();

		if ($this->form->is_valid($post))
		{
			$this->load->library('email')
				->to($this->db->select('email')->from('nf_users')->where('user_id', $user_id)->row())
				->subject('Mot de passe réinitialisé')
				->message('default', array(
					'content' => 'Votre mot de passe a bien été réinitialisé'
				))
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
					
		return new Panel(array(
				'title'   => 'Réinitialisation de votre mot de passe',
				'icon'    => 'fa-lock',
				'content' => $this->form->display()
			));
	}

	public function logout()
	{
		$this->user->logout();

		if ($this->config->nf_http_authentication)
		{
			$this->ajax();
			echo 'Vous n\'êtes plus authentifié.';
		}
		else
		{
			redirect();
		}
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/modules/user/controllers/index.php
*/