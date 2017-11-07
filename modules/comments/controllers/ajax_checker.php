<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_comments_c_ajax_checker extends Controller_Module
{
	public function delete($comment_id)
	{
		$comment = $this->db->select('user_id', 'module_id', 'module')
							->from('nf_comments')
							->where('comment_id', (int)$comment_id)
							->row();

		if ($comment && ($this->user('admin') || ($this->user() && $comment['user_id'] == $this->user('user_id'))))
		{
			return [$comment_id, $comment['module_id'], $comment['module']];
		}
	}
}
