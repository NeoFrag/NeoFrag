<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Models;

use NF\NeoFrag\Loadables\Model;

class Forum extends Model
{
	public function get_categories_list($forum_id = NULL)
	{
		$categories = [];

		foreach ($this->db	->select('c.category_id', 'c.title', 'f.forum_id', 'f.title as forum_title')
							->from('nf_forum_categories c')
							->join('nf_forum f', 'c.category_id = f.parent_id AND f.is_subforum = "0"')
							->order_by('c.order', 'f.order')
							->get() as $category)
		{
			if (!isset($categories[$category['category_id']]))
			{
				$categories[$category['category_id']] = $category['title'];
			}

			if ($category['forum_id'] && (!$forum_id || $category['forum_id'] != $forum_id))
			{
				$categories['f'.$category['forum_id']] = str_repeat('&nbsp;', 10).$category['forum_title'];
			}
		}

		return $categories;
	}

	public function get_categories()
	{
		$categories = [];
		$forums     = $this->get_forums();
		$count_read = $i = 0;

		foreach ($this->db	->select('category_id', 'title')
							->from('nf_forum_categories')
							->order_by('order', 'category_id')
							->get() as $category)
		{
			if ($this->access('forum', 'category_read', $category['category_id']))
			{
				$category['forums'] = [];

				foreach ($forums as $forum)
				{
					if ($forum['parent_id'] == $category['category_id'])
					{
						$category['forums'][] = $forum;

						$count_read += !$forum['has_unread'];
						$i++;
					}
				}

				$categories[] = $category;
			}
		}

		if ($count_read == $i)
		{
			$this->mark_all_as_read();
		}

		return $categories;
	}

	public function get_forums_tree()
	{
		$tree = [];

		foreach ($this->db	->select('category_id', 'title')
							->from('nf_forum_categories')
							->order_by('order', 'category_id')
							->get() as $category)
		{
			if ($this->access('forum', 'category_read', $category['category_id']))
			{
				$forums = [];

				foreach ($this->db	->select('f.forum_id', 'f.title')
									->from('nf_forum f')
									->join('nf_forum_url u', 'u.forum_id = f.forum_id')
									->where('f.parent_id', $category['category_id'])
									->where('f.is_subforum', FALSE)
									->where('u.forum_id', NULL)
									->order_by('f.order', 'f.forum_id')
									->get() as $forum)
				{
					$subforums = [];

					foreach ($this->db	->select('f.forum_id', 'f.title')
										->from('nf_forum f')
										->join('nf_forum_url u', 'u.forum_id = f.forum_id')
										->where('f.parent_id', $forum['forum_id'])
										->where('f.is_subforum', TRUE)
										->where('u.forum_id', NULL)
										->order_by('f.order', 'f.forum_id')
										->get() as $subforum)
					{
						$subforums[$subforum['forum_id']] = $subforum['title'];
					}

					$forums[$forum['forum_id']] = [
						'title'     => $forum['title'],
						'subforums' => $subforums
					];
				}

				if ($forums)
				{
					$tree[$category['category_id']] = [
						'title'  => $category['title'],
						'forums' => $forums
					];
				}
			}
		}

		return $tree;
	}

	public function get_forums($forum_id = NULL, $mini = FALSE)
	{
		if ($forum_id)
		{
			$this->db	->where('f.parent_id', $forum_id)
						->where('f.is_subforum', TRUE);
		}
		else
		{
			$this->db	->join('nf_forum f2', 'f2.parent_id = f.forum_id AND f2.is_subforum = "1"')
						->where('f.is_subforum', FALSE);
		}

		$forums = $this->db	->select(	'f.forum_id',
										'f.parent_id',
										'f.title',
										'f.description',
										!$forum_id ? 'f.count_messages + SUM(IFNULL(f2.count_messages, 0)) as count_messages' : 'f.count_messages',
										!$forum_id ? 'f.count_topics   + SUM(IFNULL(f2.count_topics, 0))   as count_topics'   : 'f.count_topics',
										'f.last_message_id',
										'u.id as user_id',
										'u.username',
										't.topic_id',
										't.title as last_title',
										'm.date as last_message_date',
										't.count_messages as last_count_messages',
										'u2.url',
										'u2.redirects',
										(!$forum_id ? 'COUNT(f2.forum_id)' : 0).' as subforums'
									)
									->from('nf_forum f')
									->join('nf_forum_messages m', 'm.message_id = f.last_message_id')
									->join('nf_forum_topics t',   't.topic_id = m.topic_id')
									->join('nf_user u',           'u.id = m.user_id AND u.deleted = "0"')
									->join('nf_forum_url u2',     'u2.forum_id = f.forum_id')
									->group_by('f.forum_id')
									->order_by('f.order', 'f.forum_id')
									->get();

		foreach ($forums as &$forum)
		{
			$forum['has_unread'] = $forum['url'] ? FALSE : $this->_has_unread($forum);

			if ($forum['subforums'])
			{
				foreach ($forum['subforums'] = $this->get_forums($forum['forum_id'], TRUE) as $subforum)
				{
					if (!$forum['has_unread'] && $subforum['has_unread'])
					{
						$forum['has_unread'] = TRUE;
					}

					if ($subforum['last_message_id'] > $forum['last_message_id'])
					{
						foreach (['last_message_id', 'user_id', 'username', 'topic_id', 'last_title', 'last_message_date', 'last_count_messages'] as $var)
						{
							$forum[$var] = $subforum[$var];
						}
					}
				}
			}
			else
			{
				$forum['subforums'] = [];
			}

			$forum['icon']       = icon(($forum['url'] ? 'fas fa-globe' : ($forum['has_unread'] ? 'fas fa-comments' : 'far fa-comments')).($mini ? '' : ' fa-3x'));
		}

		return $forums;
	}

	public function get_topics($forum_id)
	{
		$topics = $this->db->select('t.topic_id',
									't.title',
									't.views',
									't.count_messages',
									't.last_message_id',
									'u1.id as user_id',
									'u1.username',
									'm1.date',
									'u2.id as last_user_id',
									'u2.username as last_username',
									'm2.date as last_message_date',
									'm2.message',
									't.status IN ("-2", "1") as announce',
									't.status IN ("-2", "-1") as locked'
								)
						->from('nf_forum_topics   t')
						->join('nf_forum_messages m1', 't.message_id = m1.message_id')
						->join('nf_forum_messages m2', 't.last_message_id = m2.message_id')
						->join('nf_user u1',           'u1.id = m1.user_id AND u1.deleted = "0"')
						->join('nf_user u2',           'u2.id = m2.user_id AND u2.deleted = "0"')
						->where('t.forum_id', $forum_id)
						->order_by('IFNULL(m2.date, m1.date) DESC')
						->get();

		if ($this->user())
		{
			$forum_read = $this->db	->select('MAX(UNIX_TIMESTAMP(date))')
									->from('nf_forum_read')
									->where('user_id', $this->user->id)
									->where('forum_id', [0, $forum_id])
									->row();

			$topics_read = [];

			foreach ($this->db->select('t.topic_id', 'r.date')
									->from('nf_forum_topics_read r')
									->join('nf_forum_topics t', 't.topic_id = r.topic_id')
									->where('t.forum_id', $forum_id)
									->where('r.user_id', $this->user->id)
									->get() as $read)
			{
				$topics_read[$read['topic_id']] = strtotime($read['date']);
			}
		}

		$count_read = $i = 0;

		foreach ($topics as &$topic)
		{
			$last_message_date = strtotime($topic['last_message_date'] ?: $topic['date']);
			$unread = $this->user() && $forum_read < $last_message_date && (!isset($topics_read[$topic['topic_id']]) || $topics_read[$topic['topic_id']] < $last_message_date);
			$topic['icon'] = '	<span class="topic-icon">
								'.icon(($unread ? 'fas' : 'far').' fa-'.($topic['announce'] ? 'flag' : 'comments').' fa-3x').'
								'.($topic['locked'] ? icon('fas fa-lock fa-3x') : '').'
							</span>';

			if (!$unread)
			{
				$count_read++;
			}

			$i++;
		}

		if ($count_read == $i)
		{
			$this->mark_all_as_read($forum_id);
		}

		return $topics;
	}

	public function get_messages($topic_id, $forum_id)
	{
		return $this->db->select('message_id', 'user_id', 'message', 'UNIX_TIMESTAMP(date) as date')
						->from('nf_forum_messages')
						->where('topic_id', $topic_id)
						->order_by('message_id')
						->get();
	}

	public function check_category($category_id, $title)
	{
		$category = $this->db	->select('category_id', 'title')
								->from('nf_forum_categories')
								->where('category_id', $category_id)
								->row();

		if ($category && $title == url_title($category['title']))
		{
			return $category;
		}
		else
		{
			return FALSE;
		}
	}

	public function check_forum($forum_id, &$title)
	{
		$forum = $this->db	->select('f.forum_id', 'f.title', 'f.description', 'f.parent_id', 'f.is_subforum', 'u.url', 'IFNULL(f3.parent_id, f.parent_id) as category_id', 'COUNT(f2.forum_id) as subforums')
							->from('nf_forum f')
							->join('nf_forum f2', 'f2.parent_id = f.forum_id  AND f2.is_subforum = "1"')
							->join('nf_forum f3', 'f3.forum_id  = f.parent_id AND f.is_subforum  = "1"')
							->join('nf_forum_url u', 'u.forum_id = f.forum_id')
							->where('f.forum_id', $forum_id)
							->row();

		if ($forum && $title == url_title($forum['title']))
		{
			$title = $forum['title'];
			return $forum;
		}
		else
		{
			return FALSE;
		}
	}

	public function check_topic($topic_id, &$title)
	{
		$topic = $this->db	->select('t.title as topic_title', 't.forum_id', 'f.title', 'IFNULL(f2.parent_id, f.parent_id) as category_id', 't.views', 't.status IN ("-2", "1") as announce', 't.status IN ("-2", "-1") as locked')
							->from('nf_forum_topics t')
							->join('nf_forum        f',  't.forum_id  = f.forum_id')
							->join('nf_forum        f2', 'f2.forum_id = f.parent_id AND f.is_subforum = "1"')
							->where('t.topic_id', $topic_id)
							->row();

		if ($topic && $title == url_title($topic['topic_title']))
		{
			$title = $topic['topic_title'];
			return $topic;
		}
		else
		{
			return FALSE;
		}
	}

	public function check_message($message_id, $title)
	{
		$message = $this->db	->select('m.message_id', 't.topic_id', 't.title', 't.message_id = m.message_id as is_topic', 'm.message', 'IFNULL(f2.parent_id, f.parent_id) as category_id', 't.forum_id', 'm.user_id', 't.status IN ("-2", "-1") as locked')
								->from('nf_forum_messages m')
								->join('nf_forum_topics   t',  'm.topic_id = t.topic_id')
								->join('nf_forum          f',  't.forum_id = f.forum_id')
								->join('nf_forum          f2', 'f2.forum_id = f.parent_id AND f.is_subforum = "1"')
								->where('m.message_id', $message_id)
								->row();

		if ($message && $title == url_title($message['title']))
		{
			return $message;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_topic($forum_id, $title, $message, $announce)
	{
		$topic_id = $this->db	->ignore_foreign_keys()
								->insert('nf_forum_topics', [
									'forum_id' => (int)$forum_id,
									'title'    => $title,
									'status'   => $announce
								]);

		$message_id = $this->db	->insert('nf_forum_messages', [
									'topic_id' => $topic_id,
									'user_id'  => $this->user->id,
									'message'  => $message
								]);

		$count_topics = $this->db->select('count_topics')->from('nf_forum')->where('forum_id', $forum_id)->row();

		$this->db	->where('forum_id', $forum_id)
					->update('nf_forum', [
						'last_message_id' => $message_id,
						'count_topics'    => $count_topics + 1
					]);

		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'message_id' => $message_id
					]);

		$this->db	->insert('nf_forum_topics_read', [
						'topic_id' => $topic_id,
						'user_id'  => $this->user->id
					]);

		$this->get_topics($forum_id);

		return $topic_id;
	}

	public function add_message($topic_id, $message)
	{
		$topic = $this->db->select('count_messages', 'forum_id')->from('nf_forum_topics')->where('topic_id', $topic_id)->row();
		$count_messages = $this->db->select('count_messages')->from('nf_forum')->where('forum_id', $topic['forum_id'])->row();

		$message_id = $this->db->insert('nf_forum_messages', [
			'topic_id' => (int)$topic_id,
			'user_id'  => $this->user->id,
			'message'  => $message
		]);

		$this->db	->where('forum_id', $topic['forum_id'])
					->update('nf_forum', [
						'last_message_id' => $message_id,
						'count_messages' => $count_messages + 1
					]);

		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'last_message_id' => $message_id,
						'count_messages'  => $topic['count_messages'] + 1
					]);

		$this->db	->where('user_id', $this->user->id)
					->where('topic_id', $topic_id)
					->delete('nf_forum_topics_read');

		$this->db	->insert('nf_forum_topics_read', [
						'topic_id' => $topic_id,
						'user_id'  => $this->user->id
					]);

		$this->get_topics($topic['forum_id']);

		return $message_id;
	}

	public function add_category($title)
	{
		$category_id = $this->db->insert('nf_forum_categories', [
			'title' => $title
		]);

		$this->access->init('forum', 'category', $category_id);

		return $category_id;
	}

	public function add_forum($title, $category_id, $description, $url)
	{
		$forum_id = $this->db->insert('nf_forum', [
			'title'       => $title,
			'parent_id'   => $this->get_parent_id($category_id, $is_subforum),
			'is_subforum' => $is_subforum,
			'description' => $description
		]);

		if ($url)
		{
			$this->db->insert('nf_forum_url', [
				'forum_id' => $forum_id,
				'url'      => $url
			]);
		}

		return $forum_id;
	}

	public function edit_category($category_id, $title)
	{
		$this->db	->where('category_id', $category_id)
					->update('nf_forum_categories', [
						'title' => $title
					]);
	}

	public function delete_category($category_id)
	{
		$this->db	->where('category_id', $category_id)
					->delete('nf_forum_categories');

		foreach ($this->db->select('forum_id')->from('nf_forum')->where('parent_id', $category_id)->where('is_subforum', FALSE)->get() as $forum_id)
		{
			$this->delete_forum($forum_id);
		}

		$this->access->delete('forum', $category_id);
	}

	public function delete_forum($forum_id)
	{
		foreach ($this->db->select('forum_id')->from('nf_forum')->where('parent_id', $forum_id)->where('is_subforum', TRUE)->get() as $subforum_id)
		{
			$this->delete_forum($subforum_id);
		}

		$this->db	->where('forum_id', $forum_id)
					->delete('nf_forum');

		$this->db	->where('forum_id', $forum_id)
					->delete('nf_forum_read');
	}

	public function mark_all_as_read($forum_id = 0)
	{
		if (!$this->user())
		{
			return;
		}

		if ($forum_id)
		{
			$this->db	->where('r.user_id', $this->user->id)
						->where('t.topic_id = r.topic_id')
						->where('t.forum_id', $forum_id)
						->delete('r', 'nf_forum_topics_read as r, nf_forum_topics as t');

			$this->db	->where('user_id', $this->user->id)
						->where('forum_id', $forum_id)
						->delete('nf_forum_read');
		}
		else
		{
			$this->db	->where('user_id', $this->user->id)
						->delete('nf_forum_topics_read');

			$this->db	->where('user_id', $this->user->id)
						->delete('nf_forum_read');
		}

		$this->db->insert('nf_forum_read', [
			'user_id'  => $this->user->id,
			'forum_id' => $forum_id
		]);
	}

	public function increment_redirect($forum_id)
	{
		$this->db	->where('forum_id', $forum_id)
					->update('nf_forum_url', 'redirects = redirects + 1');
	}

	public function get_parent_id($parent_id, &$is_subforum)
	{
		$is_subforum = FALSE;

		if (strpos($parent_id, 'f') === 0)
		{
			$parent_id   = substr($parent_id, 1);
			$is_subforum = TRUE;
		}

		return $parent_id;
	}

	public function get_last_message_id($forum_id)
	{
		$message_id = $this->db	->select('m.message_id')
								->from('nf_forum_messages m')
								->join('nf_forum_topics t', 't.topic_id = m.topic_id')
								->where('t.forum_id', $forum_id)
								->order_by('message_id DESC')
								->row();

		return $message_id ?: NULL;
	}

	public function count_messages($topic_id)
	{
		return $this->db->from('nf_forum_messages')
						->where('topic_id', $topic_id)
						->count() - 1;
	}

	public function _has_unread($forum)
	{
		if (!$forum['count_topics'] || !$this->user())
		{
			return FALSE;
		}

		static $forum_reads;

		if ($forum_reads === NULL)
		{
			$forum_reads = [];

			foreach ($this->db	->select('forum_id', 'date')
								->from('nf_forum_read')
								->where('user_id', $this->user->id)
								->get() as $read)
			{
				$forum_reads[$read['forum_id']] = strtotime($read['date']);
			}

			$registration_date = strtotime($this->user->registration_date);
			if (!isset($forum_reads[0]) || $registration_date > $forum_reads[0])
			{
				$forum_reads[0] = $registration_date;
			}
		}

		$dates = [];

		if (isset($forum_reads[0]))
		{
			$dates[] = $forum_reads[0];
		}

		if (isset($forum_reads[$forum['forum_id']]))
		{
			$dates[] = $forum_reads[$forum['forum_id']];
		}

		$forum_read_date = $dates ? max($dates) : NULL;

		return empty($forum_read_date) || $forum_read_date < strtotime($forum['last_message_date']);
	}
}
