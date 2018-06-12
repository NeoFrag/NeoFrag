<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function update()
	{
		if ($update = $this->theme('admin')->update())
		{
			$this->ajax();
			return [$update];
		}
	}
}
