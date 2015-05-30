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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_members_c_search extends Controller_Module
{
	public $name = '{lang search_members}';

	public function index($results)
	{
		echo 'index {lang search_members}';
	}

	public function detail($results)
	{
		echo 'detail {lang search_members}';
	}

	public function search($keywords, $not_keywords)
	{
		$this->db	->select('user_id', 'username', 'email')
					->from('nf_users')
					->where('deleted', FALSE);

		$args = array();
		foreach ($keywords as $keyword)
		{
			array_push($args, 'username LIKE', '%'.$keyword.'%', 'OR');
		}

		call_user_func_array(array($this->db, 'where'), $args);

		if ($not_keywords)
		{
			$args = array();
			foreach ($not_keywords as $keyword)
			{
				array_push($args, 'username NOT LIKE', '%'.$keyword.'%', 'AND');
			}

			call_user_func_array(array($this->db, 'where'), $args);
		}

		return $this->db->get();
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/members/controllers/search.php
*/