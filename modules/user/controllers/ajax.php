<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function _member($user_id, $username)
	{
		return $this->view('profile_mini', $this->model()->get_user_profile($user_id));
	}

	public function login()
	{
		return $this->form2()
					->compact()
					->rule($this->form_text('login')
								->title('Pseudo ou adresse email')
								->required()
					)
					->rule($this->form_password('password')
								->title('Mot de passe')
								->required()
					)
					->rule($this->form_checkbox('remember')
								->value('on')
								->data([
									'on' => 'Se souvenir de moi'
								])
					)
					->success(function($data, $form){
						$user = $this->db	->collection('user')
											->where('deleted', FALSE)
											->where('username', $data['login'], 'OR', 'email', $data['login'])
											->row();
						//TODO admin123
						if ($user() && $this->password->is_valid($data['password'].$user->salt, $user->password, $salt = $user->salt !== ''))
						{
							if (!$salt)
							{
								$user	->set('password', $this->password->encrypt($data['password'].($salt = unique_id())))
										->set('salt', $salt)
										->update();
							}

							if ($this->config->nf_registration_validation && !$user->last_activity_date)
							{
								//Vous devez valider votre inscription, recevoir un nouveau mail de validation
								//TODO
							}
							else
							{
								$this->session->login($user, in_array('on', $data['remember']));
							}
						}
						else
						{
							//Erreur identifiants invalides
							//TODO
						}
					})
					->submit('Connexion')
					->modal('Se connecter', 'fa-sign-in')
					->button_prepend($this	->button()
											->title('Mot de passe oublié ?')
											->color('default')
											->modal_ajax('ajax/user/lost-password')
					);
	}

	public function register()
	{
		return $this->form2()
					->compact()
					->rule($this->form_text('username')
								->title('Pseudo')
								->required()
					)
					->rule($this->form_password('password')
								->title('Mot de passe')
								->required()
					)
					->rule($this->form_password('password_confirm')
								->title('Confirmation du mot de passe')
								->required()
					)
					->rule($this->form_email('email')
								->title('Adresse email')
								->required()
					)
					->captcha()
					->success(function($data, $form){
						$user = $this	->model2('user')
										->set('username', $data['username'])
										->set('password', $data['password'])
										->set('email',    $data['email'])
										->create();

						if ($this->config->nf_registration_validation)
						{
							$sent = $this	->anti_flood()
											->email
											->to($data['email'])
											->subject('Validation de votre compte')
											->message(function() use ($user){
												return [
													'content' => 'Bonjour '.$user->username.',<br /><br />Afin de valider votre inscription sur note site web, merci de bien vouloir cliquer sur le bouton ci-dessous.<br /><br /><div class="text-center"><a class="btn btn-primary" href="'.url('user/validation/'.$user->token()).'">Valider mon compte</a></div>'
												];
											})
											->send();

							if ($sent)
							{
								notify('Message envoyé');
								$this->modal->dispose();
							}
							else
							{
								$form->prepend('Une erreur s\'est produite lors de l\'envoi du message');
							}
						}
					})
					->modal('Créer un compte', 'fa-sign-in fa-rotate-90')
					->cancel();
	}

	public function lost_password()
	{
		return $this->form2()
					->compact()
					->rule($this->form_email('email')
								->title('Adresse email')
								->required()
					)
					->success(function($data, $form){
						$user = $this->db	->collection('user')
											->where('deleted', FALSE)
											->where('email', $data['email'])
											->row();

						if (!$user())
						{
							$form->prepend($this->lang('Addresse email introuvable'));
						}
						else
						{
							$sent = $this	->anti_flood()
											->email
											->to($data['email'])
											->subject('Réinitialisation de mot de passe')
											->message(function() use ($user){
												return [
													'content' => 'Bonjour '.$user->username.',<br /><br />Vous avez demandé à réinitialiser votre mot de passe. Il vous suffit de cliquer sur le bouton ci-dessous pour choisir un nouveau mot de passe.<br /><br /><div class="text-center"><a class="btn btn-primary" href="'.url('user/lost-password/'.$user->token()).'">'.$this->lang('Réinitialisation de votre mot de passe').'</a></div>'
												];
											})
											->send();

							if ($sent)
							{
								notify('Message envoyé');
								$this->modal->dispose();
							}
							else
							{
								$form->prepend('Une erreur s\'est produite lors de l\'envoi du message');
							}
						}
					})
					->modal('Récupération de mot de passe', 'fa-unlock-alt')
					->cancel();
	}

	public function _lost_password($token)
	{
		return $this->form2()
					->compact()
					->rule($this->form_password('password')
								->title('Mot de passe')
								->required()
					)
					->rule($this->form_password('password_confirm')
								->title('Confirmation du mot de passe')
								->required()
					)
					->success(function($data) use ($token){
						$token	->delete()
								->user
								->set('password', $this->password->encrypt($data['password'].($salt = unique_id())))
								->set('salt', $salt)
								->update();

						notify('Nouveau mot de passe enregistré');

						$this->session->login($token->user);
					})
					->modal('Réinitialisation de mot de passe', 'fa-unlock-alt')
					->cancel();
	}
}
