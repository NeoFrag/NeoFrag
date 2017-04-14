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
		return [$this->model2('user')->collection()->paginate($page)];
	}

	public function _group()
	{
		$args = func_get_args();
		$page = array_pop($args);

		if ($group = $this->groups->check_group($args))
		{
			return [$group['title'], $group['users'] ? $this->model2('user')->collection()->where('id', $group['users'])->paginate($page) : []];
		}
	}
}
