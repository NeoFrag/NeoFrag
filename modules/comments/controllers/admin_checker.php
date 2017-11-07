<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_comments_c_admin_checker extends Controller
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

		return [$this->pagination->get_data($comments, $page), $modules, $tab];
	}
}
