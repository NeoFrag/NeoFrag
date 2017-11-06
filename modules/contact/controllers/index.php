<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Contact\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		$rules = [];

		if (!$this->user())
		{
			$rules['email'] = [
				'label' => $this->lang('Adresse email'),
				'type'  => 'email',
				'rules' => 'required'
			];
		}

		$rules['subject'] = [
			'label' => $this->lang('Objet'),
			'rules' => 'required'
		];

		$rules['message'] = [
			'label' => $this->lang('Message'),
			'type'  => 'editor',
			'rules' => 'required'
		];

		$this->title($this->lang('Nous contacter'))
				->form
				->display_required(FALSE)
				->add_rules($rules)
				->add_captcha()
				->add_submit(icon('fa-envelope-o').' '.$this->lang('Envoyer'))
				->add_back('index');

		if ($this->form->is_valid($post))
		{
			$this	->email
					->from($this->user() ? $this->user('email') : $post['email'])
					->to($this->config->nf_contact)
					->subject($this->lang('Contact').' :: '.$post['subject'])
					->message('default', [
						'content' => function() use ($post){
							return bbcode($post['message']).($this->user() ? '<br /><br /><br />'.$this->user->link() : '');
						}
					])
					->send();

			redirect();
		}

		return $this->panel()
					->heading($this->lang('Nous contacter'), 'fa-envelope-o')
					->body($this->form->display());
	}
}
