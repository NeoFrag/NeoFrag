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
 
class m_forum_c_admin_ajax_checker extends Controller
{
	public function _categories_move()
	{
		if (($check = post_check('category_id', 'position')) && $this->db->select('1')->from('nf_forum_categories')->where('category_id', $check['category_id'])->row())
		{
			return $check;
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function move()
	{
		if (	($check = post_check('parent_id', 'forum_id', 'position')) &&
				!is_array($is_subforum = $this->db->select('is_subforum')->from('nf_forum')->where('forum_id', $check['forum_id'])->row()) &&
				(
					($is_subforum  && $this->db->select('1')->from('nf_forum')->where('forum_id', $check['parent_id'])->where('is_subforum', FALSE)->row()) ||
					(!$is_subforum && $this->db->select('1')->from('nf_forum_categories')->where('category_id', $check['parent_id'])->row())
				)
			)
		{
			return array_merge($check, [$is_subforum]);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/forum/controllers/admin_ajax_checker.php
*/