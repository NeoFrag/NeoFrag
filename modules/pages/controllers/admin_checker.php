<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_pages(), $page)];
	}

	public function _edit($page_id, $title, $tab = 'default')
	{
		if ($page = $this->model()->check_page($page_id, $title, $tab, TRUE))
		{
			return $page + [$tab];
		}
	}

	public function delete($page_id, $title)
	{
		$this->ajax();

		if ($page = $this->model()->check_page($page_id, $title, 'default', TRUE))
		{
			return [$page['page_id'], $page['title']];
		}
	}
}
