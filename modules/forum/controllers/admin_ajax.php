<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
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
