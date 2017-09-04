<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access\Models;

use NF\NeoFrag\Loadables\Model;

class Access extends Model
{
	public function add($module, $action, $id, $type, $entities, $authorized)
	{
		if (!($access_id = $this->db->select('access_id')->from('nf_access')->where('module', $module)->where('action', $action)->where('id', $id)->row()))
		{
			$access_id = $this->db->insert('nf_access', [
				'module' => $module,
				'action' => $action,
				'id'     => $id
			]);
		}

		foreach ((array)$entities as $entity)
		{
			$this->db->insert('nf_access_details', [
				'access_id'  => $access_id,
				'entity'     => $entity,
				'type'       => $type,
				'authorized' => $authorized
			]);
		}

		return $this;
	}

	public function delete($module, $action, $id, $type = NULL, $entity = NULL)
	{
		if ($type)
		{
			$this->db->where('ad.type', $type);

			if ($entity)
			{
				$this->db->where('ad.entity', $entity);
			}
		}

		$this->db	->where('a.module', $module)
					->where('a.action', $action)
					->where('a.id', $id)
					->delete('ad', 'nf_access_details ad INNER JOIN nf_access a ON a.access_id = ad.access_id');

		return $this;
	}
}
