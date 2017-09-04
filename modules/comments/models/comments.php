<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Models;

use NF\NeoFrag\Loadables\Model;

class Comments extends Model
{
	private $_modules = [];

	public function get_comments()
	{
		$comments =  $this->db	->select('module_id', 'module', 'count(*) as count')
												->from('nf_comments')
												->group_by('module_id', 'module')
												->get();

		if ($comments)
		{
			$list = [];

			foreach ($comments as $comment)
			{
				if ($tmp = $this->check_comment($comment['module'], $comment['module_id']))
				{
					$list[] = array_merge($comment, $tmp);
				}
			}

			return $list;
		}
		else
		{
			return [];
		}
	}

	public function check_comment($module_name, $module_id)
	{
		if (isset($this->_modules[$module_name]))
		{
			$module = $this->_modules[$module_name];
		}
		else
		{
			$this->_modules[$module_name] = $module = $this->module($module_name);
		}

		if (method_exists($module, 'comments'))
		{
			$comment = $module->comments($module_id);

			$comment['module_title'] = $module->get_title();
			$comment['icon']         = $module->icon;

			return $comment;
		}
	}
}
