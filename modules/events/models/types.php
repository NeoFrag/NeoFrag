<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Models;

use NF\NeoFrag\Loadables\Model;

class Types extends Model
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
