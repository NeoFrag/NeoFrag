<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [
			$this	->collection('user')
					->where('deleted', FALSE)
					->filters(
						$this	->form2()
								->rule($this->form_text('username')
											->title('Pseudo')
											->data($this->collection('user')->select('username')->where('deleted', FALSE)->array())
											->filter('_.username LIKE')
								)
								->rule($this->form_text('email')
											->title('Email')
											->data($this->collection('user')->select('email')->where('deleted', FALSE)->array())
											->filter('_.email LIKE')
								)
					)
					->paginate($page)
		];
	}

	public function _groups_edit()
	{
		if ($group = $this->groups->check_group(func_get_args()))
		{
			return [
				isset($group['id']) ? $group['id'] : 0,
				$group['unique_id'],
				$group['title'],
				$group['color'],
				$group['icon'],
				$group['hidden'],
				$group['auto']
			];
		}
	}

	public function _groups_delete()
	{
		$this->ajax();

		if ($group = $this->groups->check_group(func_get_args()))
		{
			if (!$group['auto'])
			{
				return [$group['id'], $group['title']];
			}
		}
	}

	public function _sessions($page = '')
	{
		return [NeoFrag()->collection('session')->order_by('_.last_activity DESC')->paginate($page)];
	}

	public function _sessions_delete($session_id)
	{
		$this->ajax();

		if (!$this->db->from('nf_session')->where('id', $session_id)->empty())
		{
			$this->ajax();

			return [$session_id];
		}
	}
}
