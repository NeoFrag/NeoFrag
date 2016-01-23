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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_user_m_messages extends Model
{
	public function get_messages_inbox()
	{
		//TODO db->query
		$messages = $this->db	->query('	SELECT m.message_id, m.title, UNIX_TIMESTAMP(m.date) as date, m.user_id, u.username, r.read
											FROM nf_users_messages_recipients r
											JOIN nf_users_messages            m ON r.message_id = m.message_id
											JOIN nf_users                     u ON m.user_id    = u.user_id
											WHERE r.user_id = %d', $this->user('user_id'))
								->get();

		if ($messages)
		{

			return $messages;
		}

		return FALSE;
	}

	public function get_messages_sent()
	{
		$messages = $this->db	->select('title', 'UNIX_TIMESTAMP(date)')
								->from('nf_users_messages')
								->where('user_id', $this->user('user_id'))
								->get();

		if ($messages)
		{

			return $messages;
		}

		return FALSE;
	}

	public function check_message($message_id, &$title, &$messages)
	{
		//TODO db->query
		$message = $this->db->query('	SELECT m.title, m.content, UNIX_TIMESTAMP(m.date) as date, r.read, m.user_id, u.username
										FROM nf_users_messages_recipients r
										JOIN nf_users_messages            m ON r.message_id = m.message_id
										JOIN nf_users                     u ON m.user_id    = u.user_id
										WHERE r.user_id = %d AND m.message_id = %d', $this->user('user_id'), (int)$message_id)
							->row();

		if ($message && $title == url_title($message['title']))
		{
			$title    = $message['title'];
			//TODO db->query
			$messages = $this->db->query('	SELECT r.content, UNIX_TIMESTAMP(r.date) as date, r.read, r.user_id, u.username
											FROM nf_users_messages_replies r
											JOIN nf_users                  u ON r.user_id    = u.user_id
											WHERE r.message_id = %d
											ORDER BY r.reply_id ASC', (int)$message_id)
								->get();

			unset($message['title']);
			array_unshift($messages, $message);

			return TRUE;
		}

		return FALSE;
	}

	public function insert_message($recipients_list, $title, $content)
	{
		$recipients = array();
		foreach (array_map('strtolower', array_map('trim', explode(';', $recipients_list))) as $recipient)
		{
			if (preg_match('/^groupe:(.+?)$/', $recipient, $matches))
			{
				$recipients = array_merge($recipients, $this->db->select('g.user_id')
																->from('nf_groups_lang l')
																->join('nf_users_groups g', 'NATURAL')
																->join('nf_users u', 'NATURAL')
																->where('l.title', $matches[1])
																->where('u.deleted', FALSE)
																->get()
				);
			}
			else
			{
				$recipients[] = (int)$this->db->select('user_id')->from('nf_users')->where('deleted', FALSE)->where('username', $recipient)->row();
			}
		}

		$recipients = array_diff(array_unique($recipients, SORT_NUMERIC), array($user_id = $this->user('user_id')));

		if ($recipients)
		{
			foreach ($recipients as $recipient)
			{
				$message_id = $this->db->insert('nf_users_messages', array(
					'user_id' => $user_id,
					'title'   => $title,
					'content' => $content
				));

				$this->db->insert('nf_users_messages_recipients', array(
				   'user_id'    => $recipient,
				   'message_id' => $message_id
			   ));
			}

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/user/models/messages.php
*/