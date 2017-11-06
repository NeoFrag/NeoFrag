<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Contact\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function index()
	{
		return $this->form2()
					->rule($this->form_text('subject')
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
												'content' => nl2br(strtolink($data['message'])).($this->user() ? '<br /><br />'.$this->user->view('user/profile') : '')
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
					})
					->modal($this->lang('Nous contacter'), 'fa-envelope-o')
					->cancel();
	}
}
