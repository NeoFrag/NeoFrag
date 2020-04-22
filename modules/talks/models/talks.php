<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Talks\Models;

use NF\NeoFrag\Loadables\Model;

class Talks extends Model
{
	public function get_messages($talks_id, $message_id = 0, $limit = FALSE)
	{
		$this->db	->select('m.message_id', 'm.talk_id', 'u.id as user_id', 'm.message', 'm.date', 'u.username', 'up.avatar', 'up.sex')
					->from('nf_talks_messages m')
					->join('nf_user         u',  'u.id = m.user_id AND u.deleted = "0"')
					->join('nf_user_profile up', 'u.id = up.id');

		if ($message_id && !$limit)
		{
			$this->db->where('message_id >=', $message_id);
		}
		else if ($message_id && $limit)
		{
			$this->db	->where('message_id <', $message_id)
						->limit(10);
		}
		else
		{
			$this->db->limit(10);
		}

		return $this->db	->where('talk_id', $talks_id)
							->order_by('m.message_id DESC')
							->get();
	}

	public function get_talks()
	{
		return $this->db->select('talk_id', 'name')
						->from('nf_talks')
						->order_by('name')
						->get();
	}

	public function check_talk($talk_id, $title)
	{
		$talk = $this->db	->select('talk_id', 'name')
							->from('nf_talks')
							->where('talk_id', $talk_id)
							->row();

		if ($talk && $title == url_title($talk['name']))
		{
			return $talk;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_talk($title)
	{
		$talk_id = $this->db->insert('nf_talks', [
			'name' => $title
		]);

		$this->access->init('talks', 'talks', $talk_id);
	}

	public function edit_talk($talk_id, $title)
	{
		$this->db	->where('talk_id', $talk_id)
					->update('nf_talks', [
						'name' => $title
					]);
	}

	public function delete_talk($talk_id)
	{
		$this->db	->where('talk_id', $talk_id)
					->delete('nf_talks');

		$this->access->delete('talks', $talk_id);
	}
}
