<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($tab = '', $page = '')
	{
		$comments = $this->model()->get_comments();
		$modules  = [];

		foreach ($comments as $i => $comment)
		{
			$modules[$comment['module']] = [$comment['module_title'], $comment['icon']];

			if (!in_array($tab, ['', $comment['module']]))
			{
				unset($comments[$i]);
			}
		}

		array_natsort($modules, function($a){
			return $a[0];
		});

		return [$this->module->pagination->get_data($comments, $page), $modules, $tab];
	}
}
