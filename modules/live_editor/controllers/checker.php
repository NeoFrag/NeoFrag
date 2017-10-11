<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Checker extends Controller_Module
{
	public function index()
	{
		if (!$this->user('admin'))
		{
			$this->error->unauthorized();
		}

		return [];
	}
}
