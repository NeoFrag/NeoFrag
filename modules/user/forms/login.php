<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->compact()
		->rule($this->form_text('login')
					->title('Pseudo ou adresse email')
					->required()
		)
		->rule($this->form_password('password')
					->title('Mot de passe')
					->required()
		)
		->rule($this->form_checkbox('remember')
					->value(['on'])
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
			if ($user() && $user->password($data['password']))
			{
				if ($this->config->nf_registration_validation && !$user->last_activity_date)
				{
					//Vous devez valider votre inscription, recevoir un nouveau mail de validation
					//TODO
				}
				else
				{
					$this->session->login($user, in_array('on', $data['remember']));
					refresh();
				}
			}
			else
			{
				$form->error('Identifiants invalides');
			}
		})
		->submit('Se connecter');
