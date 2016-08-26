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

		throw new Exception(NeoFrag::UNFOUND);
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

		throw new Exception(NeoFrag::UNFOUND);
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

		throw new Exception(NeoFrag::UNFOUND);
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

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/talks/controllers/ajax_checker.php
*/