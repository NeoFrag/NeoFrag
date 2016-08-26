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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_forum_c_index extends Controller_Module
{
	public function index()
	{
		$panels = [];
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = new Panel([
				'content' => $this->load->view('index', $category),
				'body'    => FALSE
			]);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel([
				'title'   => $this('forum'),
				'icon'    => 'fa-comments',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">'.$this('no_forum').'</div>'
			]);
		}
		
		if ($this->user())
		{
			$actions = new Panel([
				'content' => '<a class="btn btn-default" href="'.url('forum/mark-all-as-read.html').'" data-toggle="tooltip" title="'.$this('mark_all_as_read').'">'.icon('fa-eye').'</a>',
				'body'    => FALSE,
				'style'   => 'panel-back text-right'
			]);

			array_unshift($panels, $panels[] = $actions);
		}

		return $panels;
	}
	
	public function _forum($forum_id, $title, $category_id, $subforums, $announces, $topics)
	{
		$this	->title($title)
				->_breadcrumb($category_id, $forum_id);
		
		$panels = [];
		
		if (!empty($subforums))
		{
			$panels[] = new Panel([
				'content' => $this->load->view('index', [
					'title'  => $this('subforums'),
					'forums' => $subforums
				]),
				'body'    => FALSE
			]);
		}
		
		if (!empty($announces))
		{
			$panels[] = new Panel([
				'content' => $this->load->view('forum', [
					'title'  => $this('announces'),
					'icon'   => 'fa-flag',
					'topics' => $announces
				]),
				'body'    => FALSE
			]);
		}
		
		$panels[] = new Panel([
			'content' => $this->load->view('forum', [
				'title'  => $title,
				'icon'   => 'fa-navicon',
				'topics' => $topics
			]),
			'body'    => FALSE
		]);
		
		$content = '<a class="btn btn-default" href="'.url(($this->session->get_back() ?: 'forum.html')).'">'.$this('back').'</a>';
		
		if ($pagination = $this->pagination->get_pagination())
		{
			$content .= $pagination;
		}
		
		if ($this->access('forum', 'category_write', $category_id))
		{
			$content .= '<a class="pull-right btn btn-primary" href="'.url('forum/new/'.$forum_id.'/'.url_title($title).'.html').'">'.$this('new_topic').'</a>';
		}

		if ($this->user())
		{
			$content .= '<a class="pull-right btn btn-default" href="'.url('forum/mark-all-as-read/'.$forum_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('mark_all_as_read').'">'.icon('fa-eye').'</a>';
		}

		array_unshift($panels, $panels[] = new Panel([
			'content' => $content,
			'body'    => FALSE,
			'style'   => 'panel-back'
		]));
		
		return $panels;
	}
	
	public function _new($forum_id, $title, $category_id)
	{
		$this	->title($this('new_topic'))
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb()
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->form
				->add_rules([
					'title' => [
						'rules' => 'required'
					],
					'message' => [
						'type'  => 'editor',
						'rules' => 'required'
					]
				]);
		
		if ($this->access('forum', 'category_announce', $category_id))
		{
			$this->form->add_rules([
				'announce' => [
					'type' => 'checkbox'
				]
			]);
		}

		if ($this->form->is_valid($post))
		{
			$topic_id = $this->model()->add_topic(	$forum_id,
													$post['title'],
													$post['message'],
													!empty($post['announce']) && in_array('on', $post['announce']));

			notify('Sujet ajouté');

			redirect('forum/topic/'.$topic_id.'/'.url_title($post['title']).'.html');
		}
		
		$panels = [];
		
		if ($errors = $this->form->get_errors())
		{
			$panels[] = new Row(new Col(
				new Panel([
					'title'   => $this('fill_all_fields'),
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger'
				])
			));
		}
		
		$panels[] = new Panel([
			'title'   => $this('new_topic'),
			'icon'    => 'fa-file-text-o',
			'body'    => FALSE,
			'content' => $this->load->view('new', [
				'form_id'     => $this->form->id,
				'forum_id'    => $forum_id,
				'category_id' => $category_id,
				'title'       => $title
			])
		]);
		
		return $panels;
	}
	
	public function _topic($topic_id, $title, $forum_id, $forum_title, $category_id, $views, $nb_users, $nb_messages, $is_announce, $is_locked, $topic, $messages)
	{
		$this	->title($title)
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb()
				->js('neofrag.delete');
		
		$last_message_read = NULL;
		
		$is_last_page = $nb_messages <= $this->pagination->get_items_per_page() || $this->pagination->get_page() == ceil($nb_messages / $this->pagination->get_items_per_page());
		
		if ($this->user())
		{
			$last_message_date = $messages ? end($messages)['date'] : $topic['date'];
			$last_message_read = $this->db->select('UNIX_TIMESTAMP(date)')->from('nf_forum_topics_read')->where('user_id', $this->user('user_id'))->where('topic_id', $topic_id)->row();
			
			$forum_read = $this->db	->select('MAX(UNIX_TIMESTAMP(date))')
									->from('nf_forum_read')
									->where('user_id', $this->user('user_id'))
									->where('forum_id', [0, $forum_id])
									->row();
			
			if ($forum_read && $last_message_read)
			{
				$last_message_read = max($last_message_read, $forum_read);
			}
			else if ($forum_read)
			{
				$last_message_read = $forum_read;
			}
			
			if (empty($last_message_read) || $last_message_read < $last_message_date)
			{
				$this->db	->where('topic_id', $topic_id)
							->where('user_id', $this->user('user_id'))
							->delete('nf_forum_topics_read');
				
				$this->db->insert('nf_forum_topics_read', [
					'user_id'  => $this->user('user_id'),
					'topic_id' => $topic_id,
					'date'     => date('Y-m-d H:i:s', $last_message_date)
				]);
				
				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', 'views = views + 1');
				
				if ($is_last_page)
				{
					$this->model()->get_topics($forum_id);
				}
			}
		}
		
		$content = '<a class="btn btn-default" href="'.url($this->session->get_back() ?: 'forum/'.$forum_id.'/'.url_title($forum_title).'.html').'">'.$this('back').'</a>';
		
		if ($pagination = $this->pagination->get_pagination())
		{
			$content .= $pagination;
		}
		
		if (!$is_locked && $this->access('forum', 'category_write', $category_id))
		{
			$page = '';
		
			if ($nb_messages > $this->pagination->get_items_per_page() && $this->pagination->get_page() != ($last_page = ceil($nb_messages / $this->pagination->get_items_per_page())))
			{
				$page = url('forum/topic/'.$topic_id.'/'.url_title($title).'/page/'.$last_page.'.html');
			}
			
			$content .= '<a class="pull-right btn btn-primary" href="'.$page.'#reply">'.$this('reply').'</a>';
		}

		if (($this->user() && $topic['user_id'] == $this->user('user_id')) || $this->access('forum', 'category_delete', $category_id))
		{
			$content .= '<a class="pull-right btn btn-default delete" href="'.url('forum/message/delete/'.$topic['message_id'].'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('remove_topic').'">'.icon('fa-close').'</a>';
		}

		if ($this->access('forum', 'category_lock', $category_id))
		{
			if ($is_locked)
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('unlock_topic').'">'.icon('fa-unlock').'</a>';
			}
			else
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('lock_topic').'">'.icon('fa-lock').'</a>';
			}
		}

		if ($this->access('forum', 'category_announce', $category_id))
		{
			if ($is_announce)
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('unset_announce').'">'.icon('fa-flag-o').'</a>';
			}
			else
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="'.$this('set_announce').'">'.icon('fa-flag').'</a>';
			}
		}

		if ($this->access('forum', 'category_move', $category_id))
		{
			$this	->css('move')
					->js('move');

			$content .= '<span class="pull-right btn btn-default topic-move" data-toggle="tooltip" data-action="'.url('ajax/forum/topic/move/'.$topic_id.'/'.url_title($title).'.html').'" title="'.$this('move_topic').'">'.icon('fa-reply fa-flip-horizontal').'</span>';
		}
		
		$panels = [];
		
		if ($is_locked)
		{
			$panels[] = new Panel([
				'title'   => '<a name="reply"></a>'.$this('locked_topic'),
				'icon'    => 'fa-warning',
				'style'   => 'panel-danger'
			]);
		}
		
		$panels[] = new Panel([
			'content' => $this->load->view('topic', array_merge($topic, [
				'category_id'       => $category_id,
				'topic_id'          => $topic_id,
				'title'             => $title,
				'views'             => $views,
				'last_message_read' => $last_message_read
			])),
			'body'    => FALSE
		]);
		
		$actions = new Panel([
			'content' => $content,
			'body'    => FALSE,
			'style'   => 'panel-back'
		]);
		
		if (!empty($messages))
		{
			$panels[] = $actions;
			
			$panels[] = new Panel([
				'content' => $this->load->view('messages', [
					'category_id'       => $category_id,
					'topic_id'          => $topic_id,
					'title'             => $title,
					'nb_users'          => $nb_users,
					'nb_messages'       => $nb_messages, 
					'messages'          => $messages,
					'last_message_read' => $last_message_read
				]),
				'body'    => FALSE
			]);
		}
		
		$panels[] = $actions;
		
		if ($is_last_page && $this->access('forum', 'category_write', $category_id) && !$is_locked)
		{
			$this	->css('wbbtheme')
					->js('jquery.wysibb.min')
					->js('jquery.wysibb.fr')
					->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
					->form
					->add_rules([
						'message' => [
							'type'  => 'editor',
							'rules' => 'required'
						]
					])
					->add_submit($this('reply'));
			
			if ($this->form->is_valid($post))
			{
				$message_id = $this->model()->add_message($topic_id, $post['message']);

				//notify('success', $this('add_reply_success'));
			
				$page = '';
			
				if (++$nb_messages > $this->pagination->get_items_per_page())
				{
					$page = '/page/'.ceil($nb_messages / $this->pagination->get_items_per_page());
				}

				redirect('forum/topic/'.$topic_id.'/'.url_title($title).$page.'.html#message_'.$message_id);
			}
			
			if ($errors = $this->form->get_errors())
			{
				$panels[] = new Row(new Col(
					new Panel([
						'title'   => '<a name="reply"></a>'.$this('message_needed'),
						'icon'    => 'fa-warning',
						'style'   => 'panel-danger'
					])
				));
			}

			$panels[] = new Panel([
				'title'   => '<a name="reply"></a>'.$this('reply_topic').'',
				'icon'    => 'fa-file-text-o',
				'body'    => FALSE,
				'content' => $this->load->view('new', [
					'form_id'  => $this->form->id
				])
			]);
		}
		
		return $panels;
	}
	
	public function _topic_announce($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'status' => (string)($is_announce ? ($is_locked ? -1 : 0) : ($is_locked ? -2 : 1))
					]);
		//notify('success', $this('toggle_announce_topic'));
		redirect('forum/topic/'.$topic_id.'/'.url_title($title).'.html');
	}
	
	public function _topic_lock($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'status' => (string)($is_locked ? ($is_announce ? 1 : 0) : ($is_announce ? -2 : -1))
					]);
		//notify('success', $this('toggle_lock_topic'));
		redirect('forum/topic/'.$topic_id.'/'.url_title($title).'.html');
	}
	
	public function _topic_move($topic_id, $title, $forum_id)
	{
		$forums = [];
		
		foreach ($this->model()->get_forums_tree() as $category_id => $category)
		{
			foreach ($category['forums'] as $f_id => $forum)
			{
				$forums = array_merge($forums, [$f_id], array_keys($forum['subforums']));
			}
		}
		
		$this->load	->form
					->set_id('3a27fa5555e6f34491793733f32169db')
					->add_rules([
						'forum_id' => [
							'type'   => 'radio',
							'values' => array_flip($forums)
						]
					]);

		if ($this->form->is_valid($post) && $forum_id != $post['forum_id'])
		{
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
			
			//notify('success', ....);
		}

		redirect('forum/topic/'.$topic_id.'/'.url_title($title).'.html');
	}
	
	public function _message_edit($message_id, $topic_id, $title, $is_topic, $message, $category_id, $forum_id, $user_id, $locked)
	{
		$this	->title($this($is_topic ? 'edit_topic' : 'edit_message'))
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb($title, 'forum/topic/'.$topic_id.'/'.url_title($title).'.html')
				->breadcrumb()
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->form
				->add_rules([
					'message' => [
						'type'  => 'editor',
						'rules' => 'required'
					]
				]);
		
		if ($is_topic)
		{
			$this->form->add_rules([
				'title' => [
					'rules' => 'required'
				]
			]);
		}

		if ($this->form->is_valid($post))
		{
			if ($is_topic && $title != $post['title'])
			{
				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', [
								'title' => $post['title']
							]);
			}
			
			$this->db	->where('message_id', $message_id)
						->update('nf_forum_messages', [
							'message' => $post['message']
						]);

			//notify('success', $this('edit_message_success'));

			redirect('forum/topic/'.$topic_id.'/'.url_title($is_topic ? $post['title'] : $title).'.html');
		}
		
		$panels = [];
		
		if ($errors = $this->form->get_errors())
		{
			$panels[] = new Row(new Col(
				new Panel([
					'title'   => $this($is_topic ? 'fill_all_fields' : 'message_needed'),
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger'
				])
			));
		}
		
		$panels[] = new Panel([
			'title'   => $this($is_topic ? 'edit_topic' : 'edit_message'),
			'icon'    => 'fa-file-text-o',
			'body'    => FALSE,
			'content' => $this->load->view('new', [
				'form_id'  => $this->form->id,
				'topic_id' => $topic_id,
				'is_topic' => $is_topic,
				'title'    => $title,
				'message'  => $message,
				'user_id'  => $user_id
			])
		]);
		
		return $panels;
	}
	
	public function _message_delete($message_id, $title, $topic_id, $forum_id, $is_topic)
	{
		$this	->title($this($is_topic ? 'delete_topic' : 'delete_message'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $is_topic ? $this('topic_confirmation', $title) : $this('message_confirmation'));

		if ($this->form->is_valid())
		{
			$delete = TRUE;
			
			if ($is_topic)
			{
				$count_messages = $this->model()->count_messages($topic_id);
				
				$this->db	->where('topic_id', $topic_id)
							->delete('nf_forum_topics');
				
				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', 'count_topics = count_topics - 1');
				
				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', 'count_messages = count_messages - '.$count_messages);
			}
			else if ($this->db->select('message_id')->from('nf_forum_messages')->where('topic_id', $topic_id)->order_by('message_id DESC')->limit(1)->row() == $message_id)
			{
				$this->db	->where('message_id', $message_id)
							->delete('nf_forum_messages');

				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', 'count_messages = count_messages - 1');

				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', 'count_messages = count_messages - 1');

				if (($last_message_id = $this->db->select('message_id')->from('nf_forum_messages')->where('topic_id', $topic_id)->order_by('message_id DESC')->limit(1)->row()) &&
					 $last_message_id != $this->db->select('message_id')->from('nf_forum_topics')->where('topic_id', $topic_id)->row())
				{
					$this->db	->where('topic_id', $topic_id)
								->update('nf_forum_topics', [
									'last_message_id' => $last_message_id
								]);
				}
			}
			else
			{
				$this->db	->where('message_id', $message_id)
							->update('nf_forum_messages', [
								'message' => NULL
							]);
				
				$delete = FALSE;
			}

			if ($delete)
			{
				$last_message_id = $this->model()->get_last_message_id($forum_id);

				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', [
								'last_message_id' => $last_message_id
							]);
			}

			return 'OK';
		}

		echo $this->form->display();
	}

	public function mark_all_as_read()
	{
		$this->model()->mark_all_as_read();
		//notify('success', $this('marked_as_read'));
		redirect('forum.html');
	}
	
	public function _mark_all_as_read($forum_id, $title)
	{
		foreach (array_merge([$forum_id], $this->db->select('forum_id')->from('nf_forum')->where('parent_id', $forum_id)->where('is_subforum', TRUE)->get()) as $id)
		{
			$this->model()->mark_all_as_read($id);
		}
		
		//notify('success', $this('forum_marked_as_read', $title));
		redirect('forum/'.$forum_id.'/'.url_title($title).'.html');
	}
	
	private function _breadcrumb($category_id, $forum_id)
	{
		if ($category = $this->db->select('title')->from('nf_forum_categories')->where('category_id', $category_id)->row())
		{
			$this->breadcrumb($category, 'forum.html');
		}
		
		if (list($title, $parent_forum_id) = array_values($this->db->select('title', 'IF(is_subforum = "1", parent_id, 0)')->from('nf_forum')->where('forum_id', $forum_id)->row()))
		{
			if ($parent_forum_id && $parent_forum = $this->db->select('title')->from('nf_forum')->where('forum_id', $parent_forum_id)->row())
			{
				$this->breadcrumb($parent_forum, 'forum/'.$parent_forum_id.'/'.url_title($parent_forum).'.html');
			}
			
			$this->breadcrumb($title, 'forum/'.$forum_id.'/'.url_title($title).'.html');
		}
		
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/forum/controllers/index.php
*/