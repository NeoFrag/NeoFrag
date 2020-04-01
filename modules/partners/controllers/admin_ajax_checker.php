<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function sort()
	{
		if (($check = post_check('id', 'position')) && !$this->db->from('nf_partners')->where('partner_id', $check['id'])->empty())
		{
			return $check;
		}
	}
}
