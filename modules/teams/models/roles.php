<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Models;

use NF\NeoFrag\Loadables\Model;

class Roles extends Model
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
