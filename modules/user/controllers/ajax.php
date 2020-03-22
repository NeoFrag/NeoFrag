<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function _member($user)
	{
		return $user->view('profile');
	}

	public function auth()
	{
		$authenticators = NeoFrag()	->model2('addon')
									->get('authenticator')
									->filter('is_setup')
									->sort(function($a, $b){
										return $a->settings()->order - $b->settings()->order;
									});

		if (!$authenticators->empty())
		{
			return $this->modal('Connexion rapide', 'fas fa-user-circle')
						->large()
						->body($this->view('authenticators', [
							'authenticators' => $authenticators
						]))
						->button($this	->button()
										->title('Mot de passe oublié ?')
										->color('link')
										->modal_ajax('ajax/user/lost-password')
						)
						->button_if($this->config->nf_registration_status, $this->button()
																				->title('Créer un compte')
																				->color('secondary')
																				->modal_ajax('ajax/user/register')
						)
						->button($this	->button()
										->title('Se connecter')
										->color('primary')
										->modal_ajax('ajax/user/login')
						);
		}
		else
		{
			return $this->login();
		}
	}

	public function login()
	{
		return $this->form2('login')
					->modal('Se connecter', 'fas fa-sign-in-alt')
					->button_prepend_if($this->config->nf_registration_status, $this->button()
																					->title('Créer un compte')
																					->color('secondary')
																					->modal_ajax('ajax/user/register')
					)
					->button_prepend($this	->button()
											->title('Mot de passe oublié ?')
											->color('link')
											->modal_ajax('ajax/user/lost-password')
					);
	}

	public function register()
	{
		return $this->form2(!empty($this->config->nf_registration_charte) ? 'username password_required email charte' : 'username password_required email', $this->model2('user'))
					->compact()
					->captcha()
					->success(function($user, $form){
						if ($this->config->nf_registration_validation)
						{
							$sent = $this	->anti_flood()
											->email
											->to($user->email)
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
								$form->error('Une erreur s\'est produite lors de l\'envoi du message');
								return;
							}
						}

						$user->set_password($user->password)->create();

						if ($this->config->nf_welcome && $this->config->nf_welcome_user_id && !empty($this->config->nf_welcome_title) && !empty($this->config->nf_welcome_content))
						{
							$this->model('messages')->insert_message($user->username, $this->config->nf_welcome_title, str_replace('[pseudo]', '@'.$user->username, $this->config->nf_welcome_content), TRUE);
						}

						notify('Votre compte à bien été créé, bienvenue !');

						$this->session->login($user);

						refresh();
					})
					->modal('Créer un compte', 'fas fa-sign-in-alt fa-rotate-90')
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
							$form->error($this->lang('Addresse email introuvable'));
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
								$form->error('Une erreur s\'est produite lors de l\'envoi du message');
							}
						}
					})
					->modal('Récupération de mot de passe', 'fas fa-unlock-alt')
					->cancel();
	}

	public function _lost_password($token)
	{
		return $this->form2('password_required')
					->compact()
					->success(function($data) use ($token){
						$token	->delete()
								->user
								->set_password($data['password'])
								->update();

						notify('Nouveau mot de passe enregistré');

						$this->session->login($token->user);

						refresh();
					})
					->modal('Réinitialisation de mot de passe', 'fas fa-unlock-alt')
					->cancel();
	}
}
