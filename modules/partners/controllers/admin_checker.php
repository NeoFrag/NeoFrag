<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function add()
	{
		if (!$this->is_authorized('add_partners'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($partner_id, $name)
	{
		if (!$this->is_authorized('modify_partners'))
		{
			$this->error->unauthorized();
		}

		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return $partner;
		}
	}

	public function delete($partner_id, $name)
	{
		if (!$this->is_authorized('delete_partners'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return [$partner['partner_id'], $partner['title']];
		}
	}
}
