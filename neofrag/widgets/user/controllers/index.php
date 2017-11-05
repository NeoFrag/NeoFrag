<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_user_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		if ($this->user())
		{
			$this->css('user');

			return $this->panel()
						->heading($this->lang('member_area'))
						->body($this->view('logged', [
							'username' => $this->user('username')
						]), FALSE)
						->footer('<a href="'.url('user/logout').'">'.icon('fa-close').' '.$this->lang('logout').'</a>');
		}
		else
		{
			if ($authenticators = $this->addons->get_authenticators())
			{
				$this	->css('auth')
						->css('auth_mini');
			}

			return $this->panel()
						->heading($this->lang('member_area').($authenticators ? '<div class="pull-right">'.implode($authenticators).'</div>' : ''))
						->body($this->view('index', [
							'form_id' => $this->form->token('6e0fbe194d97aa8c83e9f9e6b5d07c66')
						]))
						->footer('<a href="'.url('user').'">'.icon('fa-sign-in  fa-rotate-90').' '.$this->lang('create_account').'</a>');
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
					->heading($this->lang('private_messages'), 'fa-envelope')
					->body($this->view('messages_inbox', [
						'messages' => $messages
					]), FALSE)
					->footer('<a class="btn btn-default" href="'.url('user/messages').'">'.icon('fa-inbox').' '.$this->lang('pm_inbox').'</a> <a class="btn btn-primary" href="'.url('user/messages/compose').'">'.icon('fa-edit').' '.$this->lang('pm_compose').'</a>');
	}
}
