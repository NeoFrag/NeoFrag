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

class m_members_m_members extends Model
{
	public function get_members($users = NULL)
	{
		if (is_array($users))
		{
			$this->db->where('u.user_id', $users);
		}
		
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.deleted', FALSE)
						->group_by('u.username')
						->order_by('u.username')
						->get();
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/members/models/members.php
*/