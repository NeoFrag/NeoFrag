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
 
class m_forum_m_forum extends Model
{
	public function get_categories_list($forum_id = NULL)
	{
		$categories = array();
		
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
	
	public function get_categories($all = FALSE)
	{
		$categories = array();
		$forums     = $this->get_forums();
		$count_read = $i = 0;
		
		foreach ($this->db	->select('category_id', 'title')
							->from('nf_forum_categories')
							->order_by('order', 'category_id')
							->get() as $category)
		{
			if ($all || is_authorized('forum', 'category_read', $category['category_id']))
			{
				$category['forums'] = array();
				
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
										'm.user_id',
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
									->join('nf_users u',          'u.user_id = m.user_id')
									->join('nf_forum_url u2',     'u2.forum_id = f.forum_id')
									->group_by('f.forum_id')
									->order_by('f.order', 'f.forum_id')
									->get();
		
		foreach ($forums as &$forum)
		{
			if ($forum['subforums'])
			{
				foreach ($forum['subforums'] = $this->get_forums($forum['forum_id'], TRUE) as $subforum)
				{
					if ($subforum['last_message_id'] > $forum['last_message_id'])
					{
						foreach (array('last_message_id', 'user_id', 'username', 'topic_id', 'last_title', 'last_message_date', 'last_count_messages') as $var)
						{
							$forum[$var] = $subforum[$var];
						}
					}
				}
			}
			else
			{
				$forum['subforums'] = array();
			}
			
			$forum['has_unread'] = $forum['url'] ? FALSE : $this->_has_unread($forum);
			$forum['icon']       = $this->assets->icon(($forum['url'] ? 'fa-globe' : 'fa-comments'.($forum['has_unread'] ? '' : '-o')).($mini ? '' : ' fa-3x').' fa-fw');
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
									'm1.user_id',
									'u1.username',
									'm1.date',
									'm2.user_id as last_user_id',
									'u2.username as last_username',
									'm2.date as last_message_date',
									'm2.message',
									't.status IN ("-2", "1") as announce',
									't.status IN ("-2", "-1") as locked'
								)
						->from('nf_forum_topics   t')
						->join('nf_forum_messages m1', 't.message_id = m1.message_id')
						->join('nf_forum_messages m2', 't.last_message_id = m2.message_id')
						->join('nf_users u1',          'u1.user_id = m1.user_id')
						->join('nf_users u2',          'u2.user_id = m2.user_id')
						->where('t.forum_id', $forum_id)
						->order_by('IFNULL(m2.date, m1.date) DESC')
						->get();
		
		if ($this->user())
		{
			$forum_read = $this->db	->select('MAX(UNIX_TIMESTAMP(date))')
									->from('nf_forum_read')
									->where('user_id', $this->user('user_id'))
									->where('forum_id', array(0, $forum_id))
									->row();
		
			$topics_read = array();
			
			foreach ($this->db->select('t.topic_id', 'r.date')
									->from('nf_forum_topics_read r')
									->join('nf_forum_topics t', 't.topic_id = r.topic_id')
									->where('t.forum_id', $forum_id)
									->where('r.user_id', $this->user('user_id'))
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
								<i class="fa fa-'.($topic['announce'] ? 'flag' : 'comments').($unread ? '' : '-o').' fa-3x fa-fw"></i>
								'.($topic['locked'] ? '<i class="fa fa-lock fa-3x fa-fw"></i>' : '').'
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
		return $this->db->select('m.message_id', 'm.user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online', 'm.message', 'UNIX_TIMESTAMP(m.date) as date')
						->from('nf_forum_messages m')
						->join('nf_users          u',  'm.user_id = u.user_id')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('m.topic_id', $topic_id)
						->group_by('m.message_id')
						->order_by('m.message_id')
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
		$topic = $this->db	->select('t.title as topic_title', 't.forum_id', 'f.title', 'f.parent_id as category_id', 't.views', 't.status IN ("-2", "1") as announce', 't.status IN ("-2", "-1") as locked')
							->from('nf_forum_topics t')
							->join('nf_forum        f', 't.forum_id  = f.forum_id')
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
		$message = $this->db	->select('t.topic_id', 't.title as topic_title', 't.message_id = m.message_id as is_topic', 'm.message', 'm.user_id', 'm.user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online', 't.forum_id', 'f.title', 'f.parent_id as category_id', 't.status IN ("-2", "-1") as locked')
								->from('nf_forum_messages m')
								->join('nf_forum_topics t',    'm.topic_id = t.topic_id')
								->join('nf_forum        f',    't.forum_id = f.forum_id')
								->join('nf_users          u',  'm.user_id = u.user_id')
								->join('nf_users_profiles up', 'u.user_id = up.user_id')
								->join('nf_sessions       s',  'u.user_id = s.user_id')
								->where('m.message_id', $message_id)
								->row();
								
		if ($message && $title == url_title($message['topic_title']))
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
								->insert('nf_forum_topics', array(
									'forum_id' => (int)$forum_id,
									'title'    => $title,
									'status'   => $announce
								));
		
		$message_id = $this->db	->check_foreign_keys()
								->insert('nf_forum_messages', array(
									'topic_id' => $topic_id,
									'user_id'  => $this->user('user_id'),
									'message'  => $message
								));

		$count_topics = $this->db->select('count_topics')->from('nf_forum')->where('forum_id', $forum_id)->row();
		
		$this->db	->where('forum_id', $forum_id)
					->update('nf_forum', array(
						'last_message_id' => $message_id,
						'count_topics'    => $count_topics + 1
					));

		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', array(
						'message_id' => $message_id
					));
		
		$this->db	->insert('nf_forum_topics_read', array(
						'topic_id' => $topic_id,
						'user_id'  => $this->user('user_id')
					));

		$this->get_topics($forum_id);

		return $topic_id;
	}
	
	public function add_message($topic_id, $message)
	{
		$topic = $this->db->select('count_messages', 'forum_id')->from('nf_forum_topics')->where('topic_id', $topic_id)->row();
		$count_messages = $this->db->select('count_messages')->from('nf_forum')->where('forum_id', $topic['forum_id'])->row();
		
		$message_id = $this->db->insert('nf_forum_messages', array(
			'topic_id' => (int)$topic_id,
			'user_id'  => $this->user('user_id'),
			'message'  => $message
		));
		
		$this->db	->where('forum_id', $topic['forum_id'])
					->update('nf_forum', array(
						'last_message_id' => $message_id,
						'count_messages' => $count_messages + 1
					));

		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', array(
						'last_message_id' => $message_id,
						'count_messages'  => $topic['count_messages'] + 1
					));
		
		$this->db	->where('user_id', $this->user('user_id'))
					->where('topic_id', $topic_id)
					->delete('nf_forum_topics_read');
		
		$this->db	->insert('nf_forum_topics_read', array(
						'topic_id' => $topic_id,
						'user_id'  => $this->user('user_id')
					));
					
		$this->get_topics($topic['forum_id']);
		
		return $message_id;
	}
	
	public function add_category($title, $is_private)
	{
		$category_id = $this->db->insert('nf_forum_categories', array(
			'title' => $title
		));
		
		$this->_category_permission($category_id, $is_private);
		
		return $category_id;
	}
	
	public function add_forum($title, $category_id, $description, $url)
	{
		$forum_id = $this->db->insert('nf_forum', array(
			'title'       => $title,
			'parent_id'   => $this->get_parent_id($category_id, $is_subforum),
			'is_subforum' => $is_subforum,
			'description' => $description
		));
		
		if ($url)
		{
			$this->db->insert('nf_forum_url', array(
				'forum_id' => $forum_id,
				'url'      => $url
			));
		}
		
		return $forum_id;
	}
	
	public function edit_category($category_id, $title, $is_private)
	{
		$this->db	->where('category_id', $category_id)
					->update('nf_forum_categories', array(
						'title' => $title
					));
		
		delete_permission('forum', $category_id);
		$this->_category_permission($category_id, $is_private);
	}
	
	private function _category_permission($category_id, $is_private)
	{
		$permissions = array('write' => 'members', 'modify' => 'admins', 'delete' => 'admins', 'announce' => 'admins', 'lock' => 'admins');
		
		if ($is_private)
		{
			$permissions = array_merge($permissions, array(
				'read' => ''
			));
		}
		
		foreach ($permissions as $permission => $group)
		{
			add_permission('forum', $category_id, 'category_'.$permission, array(
				array(
					'entity_id'  => $is_private ? 'admins' : $group,
					'type'       => 'group',
					'authorized' => TRUE
				)
			));
		}
	}
	
	public function delete_category($category_id)
	{
		$this->db	->where('category_id', $category_id)
					->delete('nf_forum_categories');
					
		foreach ($this->db->select('forum_id')->from('nf_forum')->where('parent_id', $category_id)->where('is_subforum', FALSE)->get() as $forum_id)
		{
			$this->delete_forum($forum_id);
		}
		
		delete_permission('forum', $category_id);
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
		if ($forum_id)
		{
			$this->db	->where('r.user_id', $this->user('user_id'))
						->where('t.topic_id = r.topic_id')
						->where('t.forum_id', $forum_id)
						->delete('r', 'nf_forum_topics_read as r, nf_forum_topics as t');
			
			$this->db	->where('user_id', $this->user('user_id'))
						->where('forum_id', $forum_id)
						->delete('nf_forum_read');
		}
		else
		{
			$this->db	->where('user_id', $this->user('user_id'))
						->delete('nf_forum_topics_read');
			
			$this->db	->where('user_id', $this->user('user_id'))
						->delete('nf_forum_read');
		}
		
		$this->db->insert('nf_forum_read', array(
			'user_id'  => $this->user('user_id'),
			'forum_id' => $forum_id,
		));
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
	
	public function _has_unread($forum)
	{
		if (!$forum['count_topics'] || !$this->user())
		{
			return FALSE;
		}
		
		static $forum_reads;
		
		if (is_null($forum_reads))
		{
			$forum_reads = array();
			
			foreach ($this->db	->select('forum_id', 'date')
								->from('nf_forum_read')
								->where('user_id', $this->user('user_id'))
								->get() as $read)
			{
				$forum_reads[$read['forum_id']] = strtotime($read['date']);
			}
			
			$registration_date = strtotime($this->user('registration_date'));
			if (!isset($forum_reads[0]) || $registration_date > $forum_reads[0])
			{
				$forum_reads[0] = $registration_date;
			}
			
			foreach ($this->db	->select('f.forum_id', 'f.parent_id')
								->from('nf_forum f')
								->join('nf_forum_url u', 'f.forum_id = u.forum_id')
								->where('f.is_subforum', TRUE)
								->where('u.forum_id', NULL)
								->get() as $subforum)
			{
				if (isset($forum_reads[$subforum['parent_id']]))
				{
					$forum_reads[$subforum['parent_id']] = min(isset($forum_reads[$subforum['forum_id']]) ? $forum_reads[$subforum['forum_id']] : $forum_reads[0], $forum_reads[$subforum['parent_id']]);
				}
			}
		}
		
		$dates = array();
		
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

/*
NeoFrag Alpha 0.1.1
./modules/forum/models/forum.php
*/