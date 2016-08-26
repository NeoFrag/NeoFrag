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
 
class m_forum_c_admin_ajax extends Controller
{
	public function _categories_move($category_id, $position)
	{
		$categories = [];
		
		foreach ($this->db->select('category_id')->from('nf_forum_categories')->where('category_id !=', $category_id)->order_by('order', 'category_id')->get() as $category)
		{
			$categories[] = $category;
		}
		
		foreach (array_merge(array_slice($categories, 0, $position, TRUE), [$category_id], array_slice($categories, $position, NULL, TRUE)) as $order => $category_id)
		{
			$this->db	->where('category_id', $category_id)
						->update('nf_forum_categories', [
							'order' => $order
						]);
		}
	}
	
	public function move($parent_id, $forum_id, $position, $is_subforum)
	{
		$this->db	->where('forum_id', $forum_id)
					->update('nf_forum', [
						'parent_id' => $parent_id
					]);
		
		$forums = $this->db	->select('forum_id')
							->from('nf_forum')
							->where('parent_id', $parent_id)
							->where('is_subforum', $is_subforum)
							->where('forum_id !=', $forum_id)
							->order_by('order', 'forum_id')
							->get();
		
		foreach (array_merge(array_slice($forums, 0, $position, TRUE), [$forum_id], array_slice($forums, $position, NULL, TRUE)) as $order => $forum_id)
		{
			$this->db	->where('forum_id', $forum_id)
						->update('nf_forum', [
							'order' => $order
						]);
		}
	}
}

/*
NeoFrag Alpha 0.1.1
./modules/forum/controllers/admin_ajax.php
*/