<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Models;

use NF\NeoFrag\Loadables\Model;

class Messages extends Model
{
	public function get_messages_inbox($box = 'inbox')
	{
		$inbox = [];

		if ($box == 'inbox' || $box == 'archives')
		{
			$this->db->having('SUM(IF(mr2.user_id <> '.$this->user->id.', 1, 0))');
		}
		else if ($box == 'sent')
		{
			$this->db->having('SUM(IF(mr2.user_id = '.$this->user->id.', 1, 0)) = COUNT(mr2.user_id)');
		}

		$messages = $this->db	->select('m.message_id', 'm.title', 'UNIX_TIMESTAMP(mr.date) as date')
								->from('nf_users_messages            m')
								->join('nf_users_messages_recipients r',   'r.message_id   = m.message_id'.($box != 'sent' ? ' AND r.deleted = "'.(int)($box == 'archives').'"' : ''), 'INNER')
								->join('nf_users_messages_replies    mr',  'mr.reply_id    = m.last_reply_id')
								->join('nf_users_messages_replies    mr2', 'mr2.message_id = m.message_id', 'INNER')
								->where('r.user_id', $this->user->id)
								->group_by('m.message_id')
								->get();

		foreach ($messages as $message)
		{
			if ($box == 'inbox')
			{
				$message['unread'] = !$this->db	->from('nf_users_messages_recipients r')
												->where('r.message_id', $message['message_id'])
												->where('r.user_id', $this->user->id)
												->where('r.date', NULL, 'OR', 'UNIX_TIMESTAMP(r.date) <', $message['date'])
												->empty();
			}
			else
			{
				$message['unread'] = FALSE;
			}

			$last_message = $this->db	->select('UNIX_TIMESTAMP(date) as date')
										->from('nf_users_messages_replies')
										->where('message_id', $message['message_id'])
										->order_by('reply_id DESC')
										->row(FALSE);

			if ($box == 'sent')
			{
				$last_user = $this->db	->select('r.user_id', 'u.username', 'up.avatar', 'up.sex')
										->from('nf_users_messages_recipients r')
										->join('nf_user                      u',  'r.user_id = u.id')
										->join('nf_user_profile              up', 'r.user_id = up.id')
										->where('u.id <>', $this->user->id)
										->where('r.message_id', $message['message_id'])
										->order_by('u.username')
										->row();
			}
			else
			{
				$last_user = $this->db	->select('mr.user_id', 'u.username', 'up.avatar', 'up.sex')
										->from('nf_users_messages_replies mr')
										->join('nf_user                   u',  'mr.user_id = u.id')
										->join('nf_user_profile           up', 'mr.user_id = up.id')
										->where('u.id <>', $this->user->id)
										->where('mr.message_id', $message['message_id'])
										->order_by('mr.reply_id DESC')
										->row();
			}

			$inbox[] = array_merge($message, $last_message, $last_user);
		}

		usort($inbox, function($a, $b){
			if ($a['unread'] == $b['unread'])
			{
				return $a['date'] < $b['date'];
			}

			return strcmp($b['unread'], $a['unread']);
		});

		return $inbox;
	}

	public function get_message($message_id, $title)
	{
		return $this->db->select('m.message_id', 'm.title')
						->from('nf_users_messages            m')
						->join('nf_users_messages_recipients r',  'r.message_id  = m.message_id', 'INNER')
						->where('m.message_id', $message_id)
						->where('r.user_id', $this->user->id)
						->row();
	}

	public function get_replies($message_id)
	{
		$this->db	->where('message_id', $message_id)
					->where('user_id', $this->user->id)
					->update('nf_users_messages_recipients', [
						'date' => now()
					]);

		return $this->db->select('mr.reply_id', 'mr.message_id', 'mr.message', 'UNIX_TIMESTAMP(mr.date) as date', 'mr.user_id', 'u.username', 'up.avatar', 'up.sex')
						->from('nf_users_messages_replies mr')
						->join('nf_users_messages         m',  'mr.message_id = m.message_id')
						->join('nf_user                   u',  'mr.user_id    = u.id')
						->join('nf_user_profile           up', 'mr.user_id    = up.id')
						->where('m.message_id', $message_id)
						->order_by('mr.date ASC')
						->get();
	}

	public function reply($message_id, $message)
	{
		$reply_id = $this->db->insert('nf_users_messages_replies', [
			'message_id' => $message_id,
			'user_id'    => $this->user->id,
			'message'    => $message
		]);

		$this->db	->where('message_id', $message_id)
					->update('nf_users_messages', [
						'last_reply_id' => $reply_id
					]);

		$this->db	->where('message_id', $message_id)
					->update('nf_users_messages_recipients', [
						'deleted' => FALSE
					]);
	}

	public function insert_message($recipients, $title, $message, $auto = FALSE)
	{
		$recipients = array_diff(array_unique(array_map(function($a){
			return (int)$this->db->select('id')->from('nf_user')->where('deleted', FALSE)->where('username', $a)->row();
		}, explode(';', $recipients)), SORT_NUMERIC), [$user_id = $this->user->id]);

		if ($recipients)
		{
			$message_id = $this->db	->ignore_foreign_keys()
									->insert('nf_users_messages', [
										'title' => $title
									]);

			$reply_id = $this->db	->insert('nf_users_messages_replies', [
										'message_id' => $message_id,
										'user_id'  => $author_id = $auto ? $this->config->nf_welcome_user_id : $this->user->id,
										'message'  => $message
									]);

			$this->db	->where('message_id', $message_id)
						->update('nf_users_messages', [
							'reply_id'      => $reply_id,
							'last_reply_id' => $reply_id
						]);

			$recipients[] = $author_id;

			foreach ($recipients as $recipient)
			{
				$this->db->insert('nf_users_messages_recipients', [
					'user_id'    => $recipient,
					'message_id' => $message_id,
					'date'       => $recipient == $author_id ? now() : NULL
				]);
			}

			return $message_id;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_messages_unreads()
	{
		$unreads = 0;

		$messages = $this->db	->select('m.message_id', 'm.title', 'UNIX_TIMESTAMP(mr.date) as date')
								->from('nf_users_messages            m')
								->join('nf_users_messages_recipients r',   'r.message_id   = m.message_id', 'INNER')
								->join('nf_users_messages_replies    mr',  'mr.reply_id    = m.last_reply_id')
								->join('nf_users_messages_replies    mr2', 'mr2.message_id = m.message_id', 'INNER')
								->where('r.user_id', $this->user->id)
								->group_by('m.message_id')
								->get();

		foreach ($messages as $message)
		{
			if (!$this->db	->from('nf_users_messages_recipients r')
							->where('r.message_id', $message['message_id'])
							->where('r.user_id', $this->user->id)
							->where('r.date', NULL, 'OR', 'UNIX_TIMESTAMP(r.date) <', $message['date'])
							->empty()
			)
			{
				$unreads++;
			}
		}

		return $unreads;
	}
}
