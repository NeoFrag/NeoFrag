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

class m_forum_c_index extends Controller_Module
{
	public function index()
	{
		$panels = array();
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = new Panel(array(
				'content' => $this->load->view('index', $category),
				'body'    => FALSE
			));
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel(array(
				'title'   => 'Forum',
				'icon'    => 'fa-comments',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">Il n\'y a pas de forum pour le moment</div>'
			));
		}
		
		if ($this->user())
		{
			$actions = new Panel(array(
				'content' => '<a class="btn btn-default" href="'.url('forum/mark-all-as-read.html').'" data-toggle="tooltip" title="Marquer tous les messages comme étant lus">'.icon('fa-eye').'</a>',
				'body'    => FALSE,
				'style'   => 'panel-back text-right'
			));

			array_unshift($panels, $panels[] = $actions);
		}

		return $panels;
	}
	
	public function _forum($forum_id, $title, $category_id, $subforums, $announces, $topics)
	{
		$this->title($title);
		
		$panels = array();
		
		if (!empty($subforums))
		{
			$panels[] = new Panel(array(
				'content' => $this->load->view('index', array(
					'title'  => 'Sous-catégories',
					'forums' => $subforums
				)),
				'body'    => FALSE
			));
		}
		
		if (!empty($announces))
		{
			$panels[] = new Panel(array(
				'content' => $this->load->view('forum', array(
					'title'  => 'Annonces',
					'icon'   => 'fa-flag',
					'topics' => $announces
				)),
				'body'    => FALSE
			));
		}
		
		$panels[] = new Panel(array(
			'content' => $this->load->view('forum', array(
				'title'  => $title,
				'icon'   => 'fa-navicon',
				'topics' => $topics
			)),
			'body'    => FALSE
		));
		
		$content = '<a class="btn btn-default" href="'.url(($this->session->get_back() ?: 'forum.html')).'">Retour</a>';
		
		if ($pagination = $this->pagination->get_pagination())
		{
			$content .= $pagination;
		}
		
		if (is_authorized('forum', 'category_write', $category_id))
		{
			$content .= '<a class="pull-right btn btn-primary" href="'.url('forum/new/'.$forum_id.'/'.url_title($title).'.html').'">Nouveau sujet</a>';
		}

		if ($this->user())
		{
			$content .= '<a class="pull-right btn btn-default" href="'.url('forum/mark-all-as-read/'.$forum_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Marquer tous les messages comme étant lus">'.icon('fa-eye').'</a>';
		}

		array_unshift($panels, $panels[] = new Panel(array(
			'content' => $content,
			'body'    => FALSE,
			'style'   => 'panel-back'
		)));
		
		return $panels;
	}
	
	public function _new($forum_id, $title, $category_id)
	{
		$this	->title('Nouveau sujet')
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->load->library('form')
				->add_rules(array(
					'title' => array(
						'rules' => 'required'
					),
					'message' => array(
						'type'  => 'editor',
						'rules' => 'required'
					)
				));
		
		if (is_authorized('forum', 'category_announce', $category_id))
		{
			$this->form->add_rules(array(
				'announce' => array(
					'type' => 'checkbox'
				)
			));
		}

		if ($this->form->is_valid($post))
		{
			$topic_id = $this->model()->add_topic(	$forum_id,
													$post['title'],
													$post['message'],
													!empty($post['announce']) && in_array('on', $post['announce']));

			add_alert('Succes', 'Sujet ajouté');

			redirect('forum/topic/'.$topic_id.'/'.url_title($post['title']).'.html');
		}
		
		$panels = array();
		
		if ($errors = $this->form->get_errors())
		{
			$panels[] = new Row(new Col(
				new Panel(array(
					'title'   => 'Veuillez remplir tous les champs',
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger'
				))
			));
		}
		
		$panels[] = new Panel(array(
			'title'   => 'Nouveau sujet',
			'icon'    => 'fa-file-text-o',
			'body'    => FALSE,
			'content' => $this->load->view('new', array(
				'form_id'     => $this->form->id,
				'forum_id'    => $forum_id,
				'category_id' => $category_id,
				'title'       => $title
			))
		));
		
		return $panels;
	}
	
	public function _topic($topic_id, $title, $forum_id, $forum_title, $category_id, $views, $nb_users, $nb_messages, $is_announce, $is_locked, $topic, $messages)
	{
		$this	->title($title)
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
									->where('forum_id', array(0, $forum_id))
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
				
				$this->db->insert('nf_forum_topics_read', array(
					'user_id'  => $this->user('user_id'),
					'topic_id' => $topic_id,
					'date'     => date('Y-m-d H:i:s', $last_message_date)
				));
				
				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', 'views = views + 1');
				
				if ($is_last_page)
				{
					$this->model()->get_topics($forum_id);
				}
			}
		}
		
		$content = '<a class="btn btn-default" href="'.url($this->session->get_back() ?: 'forum/'.$forum_id.'/'.url_title($forum_title).'.html').'">Retour</a>';
		
		if ($pagination = $this->pagination->get_pagination())
		{
			$content .= $pagination;
		}
		
		if (!$is_locked && is_authorized('forum', 'category_write', $category_id))
		{
			$page = '';
		
			if ($nb_messages > $this->pagination->get_items_per_page() && $this->pagination->get_page() != ($last_page = ceil($nb_messages / $this->pagination->get_items_per_page())))
			{
				$page = url('forum/topic/'.$topic_id.'/'.url_title($title).'/page/'.$last_page.'.html');
			}
			
			$content .= '<a class="pull-right btn btn-primary" href="'.$page.'#reply">Répondre</a>';
		}

		if ($topic['user_id'] == $this->user('user_id') || is_authorized('forum', 'category_delete', $category_id))
		{
			$content .= '<a class="pull-right btn btn-default delete" href="'.url('forum/message/delete/'.$topic['message_id'].'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Supprimer le sujet">'.icon('fa-close').'</a>';
		}

		if (is_authorized('forum', 'category_lock', $category_id))
		{
			if ($is_locked)
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Déverrouiller le sujet">'.icon('fa-unlock').'</a>';
			}
			else
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Verrouiller le sujet">'.icon('fa-lock').'</a>';
			}
		}

		if (is_authorized('forum', 'category_announce', $category_id))
		{
			if ($is_announce)
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Retirer des annonces">'.icon('fa-flag-o').'</a>';
			}
			else
			{
				$content .= '<a class="pull-right btn btn-default" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title).'.html').'" data-toggle="tooltip" title="Mettre en annonce">'.icon('fa-flag').'</a>';
			}
		}
		
		$panels = array();
		
		if ($is_locked)
		{
			$panels[] = new Panel(array(
				'title'   => '<a name="reply"></a>Le sujet est verrouillé',
				'icon'    => 'fa-warning',
				'style'   => 'panel-danger'
			));
		}
		
		$panels[] = new Panel(array(
			'content' => $this->load->view('topic', array_merge($topic, array(
				'category_id'       => $category_id,
				'topic_id'          => $topic_id,
				'title'             => $title,
				'views'             => $views,
				'last_message_read' => $last_message_read
			))),
			'body'    => FALSE
		));
		
		$actions = new Panel(array(
			'content' => $content,
			'body'    => FALSE,
			'style'   => 'panel-back'
		));
		
		if (!empty($messages))
		{
			$panels[] = $actions;
			
			$panels[] = new Panel(array(
				'content' => $this->load->view('messages', array(
					'category_id'       => $category_id,
					'topic_id'          => $topic_id,
					'title'             => $title,
					'nb_users'          => $nb_users,
					'nb_messages'       => $nb_messages, 
					'messages'          => $messages,
					'last_message_read' => $last_message_read
				)),
				'body'    => FALSE
			));
		}
		
		$panels[] = $actions;
		
		if ($is_last_page && is_authorized('forum', 'category_write', $category_id) && !$is_locked)
		{
			$this	->css('wbbtheme')
					->js('jquery.wysibb.min')
					->js('jquery.wysibb.fr')
					->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
					->load->library('form')
					->add_rules(array(
						'message' => array(
							'type'  => 'editor',
							'rules' => 'required'
						)
					))
					->add_submit('Répondre');
			
			if ($this->form->is_valid($post))
			{
				$message_id = $this->model()->add_message($topic_id, $post['message']);

				add_alert('Succes', 'Réponse ajoutée');
			
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
					new Panel(array(
						'title'   => '<a name="reply"></a>Veuillez remplir un message',
						'icon'    => 'fa-warning',
						'style'   => 'panel-danger'
					))
				));
			}

			$panels[] = new Panel(array(
				'title'   => '<a name="reply"></a>Répondre au sujet',
				'icon'    => 'fa-file-text-o',
				'body'    => FALSE,
				'content' => $this->load->view('new', array(
					'form_id'  => $this->form->id
				))
			));
		}
		
		return $panels;
	}
	
	public function _topic_announce($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', array(
						'status' => (string)($is_announce ? ($is_locked ? -1 : 0) : ($is_locked ? -2 : 1))
					));
		add_alert('succes', 'topic mis en annonce ou pas...');
		redirect('forum/topic/'.$topic_id.'/'.url_title($title).'.html');
	}
	
	public function _topic_lock($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', array(
						'status' => (string)($is_locked ? ($is_announce ? 1 : 0) : ($is_announce ? -2 : -1))
					));
		add_alert('succes', 'topic verrouillé ou pas...');
		redirect('forum/topic/'.$topic_id.'/'.url_title($title).'.html');
		
	}
	
	public function _message_edit($message_id, $topic_id, $title, $is_topic, $message, $category_id, $user_id, $username, $avatar, $sex, $online, $admin)
	{
		$this	->title('Édition du '.($is_topic ? 'sujet' : 'message'))
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->load->library('form')
				->add_rules(array(
					'message' => array(
						'type'  => 'editor',
						'rules' => 'required'
					)
				));
		
		if ($is_topic)
		{
			$this->form->add_rules(array(
				'title' => array(
					'rules' => 'required'
				)
			));
		}

		if ($this->form->is_valid($post))
		{
			if ($is_topic && $title != $post['title'])
			{
				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', array(
								'title' => $post['title']
							));
			}
			
			$this->db	->where('message_id', $message_id)
						->update('nf_forum_messages', array(
							'message' => $post['message']
						));

			add_alert('Succes', 'message modifié');

			redirect('forum/topic/'.$topic_id.'/'.url_title($is_topic ? $post['title'] : $title).'.html');
		}
		
		$panels = array();
		
		if ($errors = $this->form->get_errors())
		{
			$panels[] = new Row(new Col(
				new Panel(array(
					'title'   => $is_topic ? 'Veuillez remplir tous les champs' : 'Veuillez remplir un message',
					'icon'    => 'fa-warning',
					'style'   => 'panel-danger'
				))
			));
		}
		
		$panels[] = new Panel(array(
			'title'   => 'Édition du '.($is_topic ? 'sujet' : 'message'),
			'icon'    => 'fa-file-text-o',
			'body'    => FALSE,
			'content' => $this->load->view('new', array(
				'form_id'  => $this->form->id,
				'topic_id' => $topic_id,
				'is_topic' => $is_topic,
				'title'    => $title,
				'message'  => $message,
				'user_id'  => $user_id,
				'username' => $username,
				'avatar'   => $avatar,
				'sex'      => $sex,
				'online'   => $online,
				'admin'    => $admin
			))
		));
		
		return $panels;
	}
	
	public function _message_delete($message_id, $title, $topic_id, $forum_id, $is_topic)
	{
		$this	->title('Suppression du '.($is_topic ? 'sujet' : 'message'))
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer '.($is_topic ? 'le sujet <b>'.$title.'</b>' : 'ce message').' ?');

		if ($this->form->is_valid())
		{
			$delete = TRUE;
			
			if ($is_topic)
			{
				$count_messages = $this->db->select('COUNT(*) - 1')->from('nf_forum_messages')->where('topic_id', $topic_id)->row();
				
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
								->update('nf_forum_topics', array(
									'last_message_id' => $last_message_id
								));
				}
			}
			else
			{
				$this->db	->where('message_id', $message_id)
							->update('nf_forum_messages', array(
								'message' => NULL
							));
				
				$delete = FALSE;
			}

			if ($delete && $last_message_id = $this->db->select('m.message_id')->from('nf_forum_messages m')->join('nf_forum_topics t', 't.topic_id = m.topic_id')->where('t.forum_id', $forum_id)->order_by('message_id DESC')->limit(1)->row())
			{
				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', array(
								'last_message_id' => $last_message_id
							));
			}

			return 'OK';
		}

		echo $this->form->display();
	}

	public function mark_all_as_read()
	{
		$this->model()->mark_all_as_read();
		add_alert('Succes', 'Tous les messages sont désormais considéré comme étant lus');
		redirect('forum.html');
	}
	
	public function _mark_all_as_read($forum_id, $title)
	{
		foreach (array_merge(array($forum_id), $this->db->select('forum_id')->from('nf_forum')->where('parent_id', $forum_id)->where('is_subforum', TRUE)->get()) as $id)
		{
			$this->model()->mark_all_as_read($id);
		}
		
		add_alert('Succes', 'Tous les messages du forum '.$title.' sont désormais considéré comme étant lus');
		redirect('forum/'.$forum_id.'/'.url_title($title).'.html');
	}
}

/*
NeoFrag Alpha 0.1.1
./modules/forum/controllers/index.php
*/