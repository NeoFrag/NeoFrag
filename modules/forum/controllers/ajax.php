<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
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
