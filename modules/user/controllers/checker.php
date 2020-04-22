<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function index()
	{
		$this->error->unconnected();

		return [];
	}

	public function account($page = '')
	{
		$this->error->unconnected();

		return [$this->user->sessions()->order_by('_.last_activity DESC')->paginate($page)];
	}

	public function profile()
	{
		$this->error->unconnected();

		return [];
	}

	public function sessions($page = '')
	{
		$this->error->unconnected();

		return [NeoFrag()->collection('session_history')->where('_.user_id', $this->user->id)->order_by('_.date DESC')->paginate($page)];
	}

	public function _session_delete($session_id)
	{
		$this->error->unconnected();

		if (!$this->db->from('nf_session')->where('user_id', $this->user->id)->where('id', $session_id)->empty())
		{
			$this->ajax();

			return [$session_id];
		}
	}

	public function lost_password($token)
	{
		$this->error_if($this->user());

		if (($token = $this->model2('token', $token)) && $token())
		{
			return [$token];
		}
	}

	public function auth($provider)
	{
		if (($authenticator = $this->authenticator(str_replace('-', '_', $provider))) && $authenticator->is_setup())
		{
			return [$authenticator];
		}
	}

	public function _auth($page = '')
	{
		$this->error->unconnected();

		return [$this->collection('auth')->where('_.user_id', $this->user->id)->order_by('_.id')->paginate($page)];
	}

	public function logout()
	{
		if (!$this->user->id)
		{
			redirect('user');
		}

		return [];
	}

	private function _messages($box)
	{
		if ($this->user->id)
		{
			$this->css('inbox');

			return [$this->model('messages')->get_messages_inbox($box), $box];
		}
		else
		{
			redirect('user');
		}
	}

	public function _messages_inbox()
	{
		return $this->_messages('inbox', TRUE);
	}

	public function _messages_sent()
	{
		return $this->_messages('sent');
	}

	public function _messages_archives()
	{
		return $this->_messages('archives');
	}

	public function _messages_read($message_id, $title, $box = 'inbox')
	{
		if (!in_array($box, array('sent', 'archives')))
		{
			$box = 'inbox';
		}

		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			return [$message['message_id'], $message['title'], $this->model('messages')->get_replies($message_id), $box];
		}
	}

	public function _messages_compose($user_id = NULL, $username = NULL)
	{
		if (!$this->user->id)
		{
			redirect('user');
		}
		else
		{
			if ($user_id && $username)
			{
				if (($user = $this->db->select('username')->from('nf_user')->where('id', $user_id)->where('username <>', NULL)->where('deleted', FALSE)->row()) && $username == url_title($user))
				{
					$username = $user;
				}
				else
				{
					$this->error();
				}
			}

			return [$username];
		}
	}

	public function _messages_delete($message_id, $title)
	{
		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			$this->ajax();

			return $message;
		}
	}

	public function _member($id, $username)
	{
		if (($user = $this->model2('user', $id)->check($username)) && !$user->deleted)
		{
			return [$user];
		}
	}
}
