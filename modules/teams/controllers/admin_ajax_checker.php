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
 
class m_teams_c_admin_ajax_checker extends Controller
{
	public function sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_teams')->where('team_id', $check['id'])->row())
		{
			return $check;
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _roles_sort()
	{
		if (($check = post_check('id', 'position')) && $this->db->select('1')->from('nf_teams_roles')->where('role_id', $check['id'])->row())
		{
			return $check;
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/teams/controllers/admin_ajax_checker.php
*/