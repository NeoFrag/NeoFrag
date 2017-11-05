<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_forum_c_ajax extends Controller
{
	public function _topic_move($topic_id, $title, $forum_id)
	{
		return $this->view('move', [
			'topic_id'   => $topic_id,
			'title'      => $title,
			'forum_id'   => $forum_id,
			'categories' => $this->model()->get_forums_tree()
		]);
	}
}
