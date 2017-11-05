<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_talks_c_ajax_checker extends Controller_Module
{
	public function index()
	{
		if ($check = post_check('talk_id', 'message_id'))
		{
			if ($this->access('talks', 'read', $check['talk_id']))
			{
				return $check;
			}

			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
	}

	public function older()
	{
		if ($check = post_check('talk_id', 'message_id', 'position'))
		{
			if ($this->access('talks', 'read', $check['talk_id']))
			{
				return $check;
			}

			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
	}

	public function add_message()
	{
		if ($check = post_check('talk_id', 'message'))
		{
			if ($this->access('talks', 'write', $check['talk_id']))
			{
				return $check;
			}

			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
	}

	public function delete($message_id)
	{
		$this->ajax();

		$message = $this->db	->select('user_id', 'talk_id')
								->from('nf_talks_messages')
								->where('message_id', (int)$message_id)
								->row();

		if ($message)
		{
			if ($this->access('talks', 'delete', $message['talk_id']) || ($this->user() && $message['user_id'] == $this->user('user_id')))
			{
				return [$message_id, $message['talk_id']];
			}

			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
	}
}
