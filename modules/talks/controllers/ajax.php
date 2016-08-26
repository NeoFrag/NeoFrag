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

class m_talks_c_ajax extends Controller_Module
{
	public function index($talk_id, $message_id)
	{
		echo $this->load->view('index', [
			'messages' => $this->model()->get_messages($talk_id, $message_id)
		]);
	}
	
	public function older($talk_id, $message_id, $position)
	{
		echo $this->load->view('index', [
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
				'user_id' => $this->user('user_id'),
				'message' => utf8_htmlentities($message)
			]);
		}
	}
	
	public function delete($message_id, $talk_id)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_message_ajax'));

		if ($this->form->is_valid())
		{
			if ($this->db->select('message_id')->from('nf_talks_messages')->where('talk_id', $talk_id)->order_by('message_id DESC')->limit(1)->row() == $message_id)
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

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/talks/controllers/ajax.php
*/