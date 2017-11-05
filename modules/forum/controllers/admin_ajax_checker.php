<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_forum_c_admin_ajax_checker extends Controller
{
	public function _categories_move()
	{
		if (($check = post_check('category_id', 'position')) && $this->db->select('1')->from('nf_forum_categories')->where('category_id', $check['category_id'])->row())
		{
			return $check;
		}
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
	}
}
