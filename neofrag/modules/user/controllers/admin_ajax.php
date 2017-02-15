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

class m_user_c_admin_ajax extends Controller
{
	public function _groups_sort($group_id, $position)
	{
		$groups = array_filter($this->groups(), function($a){
			return $a['auto'] != 'neofrag';
		});

		array_walk($groups, function(&$a, $id){
			if (empty($a['id']))
			{
				$a['id'] = $this->db->insert('nf_groups', [
					'name'  => $id,
					'color' => $a['color'],
					'icon'  => $a['icon'],
					'auto'  => TRUE
				]);
			}
		});

		$group = $groups[$group_id];

		unset($groups[$group_id]);

		$groups = array_values(array_map(function($a){
			return $a['id'];
		}, $groups));

		foreach (array_merge(array_slice($groups, 0, --$position, TRUE), [$group['id']], array_slice($groups, $position, NULL, TRUE)) as $order => $group_id)
		{
			$this->db	->where('group_id', $group_id)
						->update('nf_groups', [
							'order' => $order
						]);
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/modules/user/controllers/admin_ajax.php
*/