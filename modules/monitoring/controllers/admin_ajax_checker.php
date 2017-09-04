<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function index()
	{
		if ($check = post_check('refresh'))
		{
			$this->extension('json');
			return [$check['refresh']];
		}
	}

	public function backup()
	{
		$this->extension('json');
		return [];
	}

	public function update()
	{
		$this->extension('json');
		return [];
	}
}
