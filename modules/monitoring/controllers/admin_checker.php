<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Checker extends Controller_Module
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
