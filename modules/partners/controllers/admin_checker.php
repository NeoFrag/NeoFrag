<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function _edit($partner_id, $name)
	{
		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return $partner;
		}
	}

	public function delete($partner_id, $name)
	{
		$this->ajax();

		if ($partner = $this->model()->check_partner($partner_id, $name))
		{
			return [$partner['partner_id'], $partner['title']];
		}
	}
}
