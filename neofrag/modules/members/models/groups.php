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

class m_members_m_groups extends Model
{
	public function add_group($title, $color, $icon, $lang)
	{
		$group_id = $this->db->insert('nf_groups', [
			'name'  => url_title($title),
			'color' => $color,
			'icon'  => $icon,
			'auto'  => FALSE
		]);
		
		$this->db->insert('nf_groups_lang', [
			'group_id' => $group_id,
			'lang'     => $lang,
			'title'    => $title
		]);
	}
	
	public function edit_group($group_id, $title, $color, $icon, $lang, $auto)
	{
		$group = [
			'color' => $color,
			'icon'  => $icon
		];
		
		if (!$auto)
		{
			$group['name'] = url_title($title);
			
			$this->db	->where('group_id', $group_id)
						->update('nf_groups_lang', [
				'lang'  => $lang,
				'title' => $title
			]);
		}
		
		$this->db	->where('group_id', $group_id)
					->update('nf_groups', $group);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/members/models/groups.php
*/