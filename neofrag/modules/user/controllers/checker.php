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

class m_user_c_checker extends Controller_Module
{
	public function edit()
	{
		if (!$this->user())
		{
			throw new Exception(NeoFrag::UNCONNECTED);
		}
	}
	
	public function sessions($page = '')
	{
		if ($this->user())
		{
			return [$this->pagination->get_data($this->user->get_sessions_history(), $page)];
		}
		
		throw new Exception(NeoFrag::UNCONNECTED);
	}
	
	public function _session_delete($session_id)
	{
		$this->ajax();

		if (!$this->user())
		{
			throw new Exception(NeoFrag::UNCONNECTED);
		}
		else if ($this->db->select('1')->from('nf_sessions')->where('user_id', $this->user('user_id'))->where('session_id', $session_id)->row())
		{
			return [$session_id];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	public function login($error = 0)
	{
		if ($this->user())
		{
			redirect('user.html');
		}
		
		return [$error];
	}

	public function _lost_password($key_id)
	{
		if ($this->user())
		{
			redirect('user.html');
		}

		if ($user_id = $this->model()->check_key($key_id))
		{
			return [$key_id, (int)$user_id];
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function logout()
	{
		if (!$this->user())
		{
			redirect('user.html');
		}
	}

	private function _messages($page, $box)
	{
		if ($this->user())
		{
			$this->css('inbox');

			return [$this->pagination->fix_items_per_page(10)->get_data($this->model('messages')->get_messages_inbox($box), $page)];
		}
		else
		{
			redirect('user.html');
		}
	}

	public function _messages_inbox($page = '')
	{
		return $this->_messages($page, 'inbox');
	}

	public function _messages_sent($page = '')
	{
		return $this->_messages($page, 'sent');
	}

	public function _messages_archives($page = '')
	{
		return $this->_messages($page, 'archives');
	}

	public function _messages_read($message_id, $title)
	{
		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			return array_merge($message, [$this->model('messages')->get_replies($message_id)]);
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _messages_compose($user_id = NULL, $username = NULL)
	{
		if (!$this->user())
		{
			redirect('user.html');
		}
		else
		{
			if ($user_id && $username)
			{
				if (($user = $this->db->select('username')->from('nf_users')->where('user_id', $user_id)->where('deleted', FALSE)->row()) && $username == url_title($user))
				{
					$username = $user;
				}
				else
				{
					throw new Exception(NeoFrag::UNFOUND);
				}
			}

			return [$username];
		}
	}

	public function _messages_delete($message_id, $title)
	{
		$this->ajax();

		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			return $message;
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/user/controllers/checker.php
*/