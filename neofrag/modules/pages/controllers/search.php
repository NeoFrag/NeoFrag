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

class m_pages_c_search extends Controller_Module
{
	public $name = '{lang general}';

	public function index($results)
	{
		echo '{lang index_general}';
	}

	public function detail($results)
	{
		echo '{lang detail_general}';
	}

	public function search($keywords, $not_keywords)
	{
		$this->db	->select('p.name', 'l.lang', 'l.title', 'l.subtitle', 'l.content')
					->from('nf_pages_lang l')
					->join('nf_pages p', 'NATURAL');

		$args = array();
		foreach ($keywords as $keyword)
		{
			array_push($args, 'p.name LIKE',     '%'.$keyword.'%', 'OR'
							, 'l.title LIKE',    '%'.$keyword.'%', 'OR'
							, 'l.subtitle LIKE', '%'.$keyword.'%', 'OR'
							, 'l.content LIKE',  '%'.$keyword.'%', 'OR');
		}

		call_user_func_array(array($this->db, 'where'), $args);

		if ($not_keywords)
		{
			$args = array();
			foreach ($not_keywords as $keyword)
			{
				array_push($args, 'p.name NOT LIKE',     '%'.$keyword.'%', 'AND'
					, 'l.title NOT LIKE',    '%'.$keyword.'%', 'AND'
					, 'l.subtitle NOT LIKE', '%'.$keyword.'%', 'AND'
					, 'l.content NOT LIKE',  '%'.$keyword.'%', 'AND');
			}

			call_user_func_array(array($this->db, 'where'), $args);
		}

		return $this->db->get();
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/pages/controllers/search.php
*/