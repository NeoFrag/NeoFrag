<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this->rule($this->form_text('subject')
				->title('Votre objet')
				->required())
	->rule_if(!$this->user->email, $this->form_email('email')
										->title('Votre adresse email')
										->required())
	->rule($this->form_textarea('message')
				->title('Votre message')
				->required())
	->captcha()
	->submit('Envoyer')
	->success(function($data, $form){
		$sent = $this	->anti_flood()
						->email
						->from($this->user->email ?: $data['email'])
						->to($this->config->nf_contact)
						->subject($data['subject'])
						->message(function() use ($data){
							return [
								'content' => nl2br(strtolink($data['message'])).($this->user() ? '<br /><br />'.$this->user->view('profile') : '')
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
	});
