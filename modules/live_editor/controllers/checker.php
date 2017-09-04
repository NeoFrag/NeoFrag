<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function index()
	{
		if (!$this->user('admin'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		return [];
	}
}
