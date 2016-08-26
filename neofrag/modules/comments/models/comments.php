<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_comments_m_comments extends Model
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
			$this->_modules[$module_name] = $module = $this->load->module($module_name);
		}

		if (method_exists($module, 'comments'))
		{
			$comment = $module->comments($module_id);
			
			$comment['module_title'] = $module->get_title();
			$comment['icon']         = $module->template->parse($module->icon, [], $module->load);

			return $comment;
		}
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/comments/models/comments.php
*/