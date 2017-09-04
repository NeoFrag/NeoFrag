<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Models;

use NF\NeoFrag\Loadables\Model;

class Groups extends Model
{
	public function add_group($title, $color, $icon, $hidden, $lang)
	{
		$group_id = $this->db->insert('nf_groups', [
			'name'   => url_title($title),
			'color'  => $color,
			'icon'   => $icon,
			'hidden' => $hidden,
			'auto'   => FALSE
		]);

		$this->db->insert('nf_groups_lang', [
			'group_id' => $group_id,
			'lang'     => $lang,
			'title'    => $title
		]);
	}

	public function edit_group($group_id, $title, $color, $icon, $hidden, $lang, $auto)
	{
		$group = [
			'color'  => $color,
			'icon'   => $icon,
			'hidden' => $hidden
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
