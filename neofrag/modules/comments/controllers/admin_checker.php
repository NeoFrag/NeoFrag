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

class m_comments_c_admin_checker extends Controller
{
	public function index($tab = 'default', $page = '')
	{
		$comments = $this->model()->get_comments();
		$modules  = [];

		foreach ($comments as $i => $comment)
		{
			$modules[$comment['module']] = [$comment['module_title'], $comment['icon']];

			if (!in_array($tab, ['default', $comment['module']]))
			{
				unset($comments[$i]);
			}
		}

		array_natsort($modules, function($a){
			return $a['title'];
		});

		return [$this->pagination->get_data($comments, $page), $modules, $tab];
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/comments/controllers/admin_checker.php
*/