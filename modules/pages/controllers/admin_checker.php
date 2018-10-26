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

	public function add()
	{
		if (!$this->is_authorized('add_pages'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($page_id, $title, $tab = 'default')
	{
		if (!$this->is_authorized('modify_pages'))
		{
			$this->error->unauthorized();
		}

		if ($page = $this->model()->check_page($page_id, $title, $tab, TRUE))
		{
			return $page + [$tab];
		}
	}

	public function delete($page_id, $title)
	{
		if (!$this->is_authorized('delete_pages'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($page = $this->model()->check_page($page_id, $title, 'default', TRUE))
		{
			return [$page['page_id'], $page['title']];
		}
	}
}
