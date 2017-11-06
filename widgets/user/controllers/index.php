<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		if ($this->user())
		{
			$this->css('user');

			return $this->panel()
						->heading($this->lang('Espace membre'))
						->body($this->view('logged', [
							'username' => $this->user('username')
						]), FALSE)
						->footer('<a href="'.url('user/logout').'">'.icon('fa-close').' '.$this->lang('Se déconnecter').'</a>');
		}
		else
		{
			if ($authenticators = NeoFrag()->model2('addon')->get('authenticator')->filter('is_enabled')->__toArray())
			{
				$this	->css('auth')
						->css('auth_mini');
			}

			return $this->panel()
						->heading($this->lang('Espace membre').($authenticators ? '<div class="pull-right">'.implode($authenticators).'</div>' : ''))
						->body($this->view('index', [
							'form_id' => $this->form->token('6e0fbe194d97aa8c83e9f9e6b5d07c66')
						]))
						->footer('<a href="'.url('user').'">'.icon('fa-sign-in  fa-rotate-90').' '.$this->lang('Créer un compte').'</a>');
		}
	}

	public function index_mini($config = [])
	{
		return $this->view('index_mini', $config);
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

		return $this->panel()
					->heading($this->lang('Messages privés'), 'fa-envelope')
					->body($this->view('messages_inbox', [
						'messages' => $messages
					]), FALSE)
					->footer('<a class="btn btn-default" href="'.url('user/messages').'">'.icon('fa-inbox').' '.$this->lang('Boîte de réception').'</a> <a class="btn btn-primary" href="'.url('user/messages/compose').'">'.icon('fa-edit').' '.$this->lang('Rédiger').'</a>');
	}
}
