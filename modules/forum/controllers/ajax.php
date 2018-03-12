<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function _topic_move($topic_id, $forum_id)
	{
		$forums = [];

		foreach ($this->model()->get_forums_tree() as $category_id => $category)
		{
			foreach ($category['forums'] as $f_id => $forum)
			{
				$forums = array_merge($forums, [$f_id], array_keys($forum['subforums']));
			}
		}

		return $this->modal('Choisissez un forum pour déplacer')
					->body($this->view('move', [
						'topic_id'   => $topic_id,
						'current'    => $forum_id,
						'categories' => $this->model()->get_forums_tree()
					]))
					->callback($this->form2()
									->rule($this->form_radio('forum_id')
												->data(array_flip($forums))
												->check(function($post) use ($forum_id){
													if ($forum_id == $post['forum_id'])
													{
														return 'Veuillez choisir un forum différent';
													}
												})
									)
									->success(function($post) use ($topic_id, $forum_id){
										$this->db	->where('topic_id', $topic_id)
													->update('nf_forum_topics', [
														'forum_id' => $post['forum_id']
													]);

										$count_messages = $this->model()->count_messages($topic_id);

										$this->db	->where('forum_id', $forum_id)
													->update('nf_forum', 'count_topics = count_topics - 1');

										$this->db	->where('forum_id', $forum_id)
													->update('nf_forum', 'count_messages = count_messages - '.$count_messages);

										$last_message_id = $this->model()->get_last_message_id($forum_id);

										$this->db	->where('forum_id', $forum_id)
													->update('nf_forum', [
														'last_message_id' => $last_message_id
													]);

										$this->db	->where('forum_id', $post['forum_id'])
													->update('nf_forum', 'count_topics = count_topics + 1');

										$this->db	->where('forum_id', $post['forum_id'])
													->update('nf_forum', 'count_messages = count_messages + '.$count_messages);


										$last_message_id = $this->model()->get_last_message_id($post['forum_id']);

										$this->db	->where('forum_id', $post['forum_id'])
													->update('nf_forum', [
														'last_message_id' => $last_message_id
													]);

										refresh();
									})
					)
					->cancel()
					->submit('Déplacer');
	}
}
