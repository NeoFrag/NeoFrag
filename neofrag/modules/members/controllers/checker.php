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

class m_members_c_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_members(), $page)];
	}

	public function _member($user_id, $username)
	{
		if ($this->model()->check_member($user_id, $username))
		{
			return [$user_id, $username];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _group()
	{
		$args = func_get_args();
		$page = array_pop($args);
		
		if ($group = $this->groups->check_group($args))
		{
			return [$group['title'], $this->pagination->get_data($this->model()->get_members($group['users']), $page)];
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/members/controllers/checker.php
*/