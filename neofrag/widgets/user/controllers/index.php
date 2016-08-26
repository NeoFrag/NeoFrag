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

class w_user_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		if ($this->user())
		{
			$this->css('user');

			return new Panel([
				'title'   => $this('member_area'),
				'content' => $this->load->view('logged', [
					'username' => $this->user('username')
				]),
				'body'    => FALSE,
				'footer'  => '<a href="'.url('user/logout.html').'">'.icon('fa-close').' '.$this('logout').'</a>'
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('member_area'),
				'content' => $this->load->view('index', [
					'form_id' => '6e0fbe194d97aa8c83e9f9e6b5d07c66'
				]),
				'footer'  => '<a href="'.url('user.html').'">'.icon('fa-sign-in  fa-rotate-90').' '.$this('create_account').'</a>'
			]);
		}
	}
	
	public function index_mini($config = [])
	{
		return $this->load->view('index_mini', $config);
	}
	
	public function messages_inbox($config = [])
	{
		$messages = $this->db	->select('m.message_id', 'm.title', 'IFNULL(r.content, m.content) as content', 'IFNULL(r.date, m.date) as date', 'm.user_id', 'u.username', 'up.avatar', 'up.sex')
								->from('nf_users_messages_recipients mr')
								->join('nf_users_messages_replies r', 'r.message_id = mr.message_id')
								->join('nf_users_messages m', 'm.message_id = mr.message_id')
								->join('nf_users u', 'u.user_id = m.user_id')
								->join('nf_users_profiles up', 'up.user_id = u.user_id')
								->where('r.user_id <>', $this->user('user_id'))
								->where('mr.user_id', $this->user('user_id'))
								->where('IFNULL(r.read, mr.read)', FALSE)
								->get();
		
		return new Panel([
			'title'   => $this('private_messages'),
			'icon'    => 'fa-envelope',
			'content' => $this->load->view('messages_inbox', [
				'messages' => $messages
			]),
			'body'    => FALSE,
			'footer'  => '<a class="btn btn-default" href="'.url('user/messages.html').'">'.icon('fa-inbox').' '.$this('pm_inbox').'</a> <a class="btn btn-primary" href="'.url('user/messages/compose.html').'">'.icon('fa-edit').' '.$this('pm_compose').'</a>'
		]);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/widgets/user/controllers/index.php
*/