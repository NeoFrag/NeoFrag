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
			return array($this->load->library('pagination')->get_data($this->user->get_sessions_history(), $page));
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
			return array($session_id);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	public function login($error = 0)
	{
		if ($this->user())
		{
			redirect('user.html');
		}
		
		return array($error);
	}

	public function _lost_password($key_id)
	{
		if ($this->user())
		{
			redirect('user.html');
		}

		if ($user_id = $this->model()->check_key($key_id))
		{
			return array($key_id, (int)$user_id);
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
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/user/controllers/checker.php
*/