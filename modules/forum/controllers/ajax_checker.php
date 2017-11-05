<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */
 
class m_forum_c_ajax_checker extends Controller
{
	public function _topic_move($topic_id, $title)
	{
		if ($topic = $this->model()->check_topic($topic_id, $title))
		{
			if ($this->access('forum', 'category_move', $topic['category_id']))
			{
				return [$topic_id, $topic['topic_title'], $topic['forum_id']];
			}
			else
			{
				throw new Exception(NeoFrag::UNAUTHORIZED);
			}
		}
	}
}
