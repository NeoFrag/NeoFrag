<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_pages_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_pages(), $page)];
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
