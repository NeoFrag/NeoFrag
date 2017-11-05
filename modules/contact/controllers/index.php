<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_contact_c_index extends Controller_Module
{
	public function index()
	{
		$rules = [];

		if (!$this->user())
		{
			$rules['email'] = [
				'label' => $this->lang('email'),
				'type'  => 'email',
				'rules' => 'required'
			];
		}

		$rules['subject'] = [
			'label' => $this->lang('subject'),
			'rules' => 'required'
		];

		$rules['message'] = [
			'label' => $this->lang('message'),
			'type'  => 'editor',
			'rules' => 'required'
		];

		$this->title($this->lang('contact_us'))
				->form
				->display_required(FALSE)
				->add_rules($rules)
				->add_captcha()
				->add_submit(icon('fa-envelope-o').' '.$this->lang('send'))
				->add_back('index');

		if ($this->form->is_valid($post))
		{
			$this	->email
					->from($this->user() ? $this->user('email') : $post['email'])
					->to($this->config->nf_contact)
					->subject($this->lang('contact').' :: '.$post['subject'])
					->message('default', [
						'content' => function() use ($post){
							return bbcode($post['message']).($this->user() ? '<br /><br /><br />'.$this->user->link() : '');
						}
					])
					->send();

			redirect();
		}

		return $this->panel()
					->heading($this->lang('contact_us'), 'fa-envelope-o')
					->body($this->form->display());
	}
}
