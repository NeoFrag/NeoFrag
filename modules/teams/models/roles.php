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

class m_teams_m_roles extends Model
{
	public function get_roles()
	{
		return $this->db	->select('role_id', 'title')
							->from('nf_teams_roles')
							->order_by('order', 'role_id')
							->get();
	}
	
	public function check_role($role_id, $title)
	{
		$role = $this->db	->select('role_id', 'title')
							->from('nf_teams_roles')
							->where('role_id', $role_id)
							->row();
		
		if ($role && $title == url_title($role['title']))
		{
			return $role;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function add_role($title)
	{
		$this->db->insert('nf_teams_roles', [
			'title' => $title
		]);
	}

	public function edit_role($role_id, $title)
	{
		$this->db	->where('role_id', $role_id)
					->update('nf_teams_roles', [
						'title' => $title
					]);
	}
	
	public function delete_role($role_id)
	{
		$this->db	->where('role_id', $role_id)
					->delete('nf_teams_roles');
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/teams/models/roles.php
*/