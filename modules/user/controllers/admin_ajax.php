<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
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
