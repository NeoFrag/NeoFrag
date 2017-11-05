<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_members_c_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_members(), $page)];
	}

	public function _group()
	{
		$args = func_get_args();
		$page = array_pop($args);
		
		if ($group = $this->groups->check_group($args))
		{
			return [$group['title'], $group['users'] ? $this->pagination->get_data($this->model()->get_members($group['users']), $page) : []];
		}
	}
}
