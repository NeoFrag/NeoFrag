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

class m_events_m_types extends Model
{
	public function check_type($type_id, $title)
	{
		$type = $this->db->from('nf_events_types')
						->where('type_id', $type_id)
						->row();

		if ($type && $title == url_title($type['title']))
		{
			return $type;
		}
	}

	public function get_types()
	{
		static $types;

		if ($types === NULL)
		{
			$types = [];

			foreach ($this->db->from('nf_events_types')->order_by('title')->get() as $type)
			{
				if ($this->access('events', 'access_events_type', $type['type_id']))
				{
					$types[$type['type_id']] = $type;
				}
			}
		}

		return $types;
	}

	public function get_types_list()
	{
		return ['Standard', 'Match'];
	}

	public function add($type, $title, $color, $icon)
	{
		$type_id = $this->db->insert('nf_events_types', [
			'type'  => $type,
			'title' => $title,
			'color' => $color,
			'icon'  => $icon
		]);

		$this->access->init('events', 'type', $type_id);

		return $type_id;
	}

	public function edit($type_id, $type, $title, $color, $icon)
	{
		$this->db	->where('type_id', $type_id)
					->update('nf_events_types', [
						'type'  => $type,
						'title' => $title,
						'color' => $color,
						'icon'  => $icon
					]);
	}

	public function delete($type_id)
	{
		$this->db	->where('type_id', $type_id)
					->delete('nf_events_types');
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/events/models/types.php
*/