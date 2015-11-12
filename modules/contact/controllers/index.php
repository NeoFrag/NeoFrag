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

class m_contact_c_index extends Controller_Module
{
	public function index()
	{
		$rules = array();
		
		if (!$this->user())
		{
			$rules['email'] = array(
				'label' => $this('email'),
				'type'  => 'email',
				'rules' => 'required'
			);
		}
		
		$rules['subject'] = array(
			'label' => $this('subject'),
			'rules' => 'required'
		);
		
		$rules['message'] = array(
			'label' => $this('message'),
			'type'  => 'editor',
			'rules' => 'required'
		);
		
		$this->title($this('contact_us'))
				->load->library('form')
				->display_required(FALSE)
				->add_rules($rules)
				->add_submit(icon('fa-envelope-o').' '.$this('send'))
				->add_back('index.html');
		
		if ($this->form->is_valid($post))
		{
			$this->load->library('email')
				->from($this->user() ? $this->user('email') : $post['email'])
				->to($this->config->nf_contact)
				->subject($this('contact').' :: '.$post['subject'])
				->message('default', array(
					'content' => bbcode($post['message']).($this->user() ? '<br /><br /><br />'.$this->user->link() : '')
				))
				->send();
			
			redirect();
		}

		return new Panel(array(
			'title'   => $this('contact_us'),
			'icon'    => 'fa-envelope-o',
			'content' => $this->form->display()
		));
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/contact/controllers/index.php
*/