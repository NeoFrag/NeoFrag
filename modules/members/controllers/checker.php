<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Members\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [NeoFrag()->collection('user')->paginate($page)];
	}

	public function _group()
	{
		$args = func_get_args();
		$page = array_pop($args);

		if ($group = $this->groups->check_group($args))
		{
			return [$group['title'], $group['users'] ? NeoFrag()->collection('user')->where('id', $group['users'])->paginate($page) : []];
		}
	}
}
