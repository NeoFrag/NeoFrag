<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Talks\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function index($talk_id, $message_id)
	{
		return $this->view('index', [
			'messages' => $this->model()->get_messages($talk_id, $message_id)
		]);
	}

	public function older($talk_id, $message_id, $position)
	{
		return $this->view('index', [
			'position' => $position,
			'user_id'  => $this->db->select('user_id')->from('nf_talks_messages')->where('message_id', $message_id)->row(),
			'messages' => $this->model()->get_messages($talk_id, $message_id, TRUE)
		]);
	}

	public function add_message($talk_id, $message)
	{
		if ($message = trim($message))
		{
			$this->db->insert('nf_talks_messages', [
				'talk_id' => $talk_id,
				'user_id' => $this->user->id,
				'message' => utf8_htmlentities($message)
			]);
		}
	}

	public function delete($message_id, $talk_id)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer ce message ?'));

		if ($this->form()->is_valid())
		{
			if ($this->db->select('message_id')->from('nf_talks_messages')->where('talk_id', $talk_id)->order_by('message_id DESC')->row() == $message_id)
			{
				$this->db	->where('message_id', $message_id)
							->delete('nf_talks_messages');
			}
			else
			{
				$this->db	->where('message_id', $message_id)
							->update('nf_talks_messages', [
								'message' => NULL
							]);
			}

			return 'OK';
		}

		return $this->form()->display();
	}
}
