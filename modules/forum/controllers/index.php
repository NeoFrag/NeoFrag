<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		$panels = $this->array;

		foreach ($this->model()->get_categories() as $category)
		{
			$panels->append($this->panel()->body($this->view('index', $category), FALSE));
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Forum'), 'fas fa-comments')
									->body('<div class="text-center">'.$this->lang('Aucun forum').'</div>')
									->color('info'));
		}

		if ($this->user())
		{
			$actions = $this->panel()
							->body('<a class="btn btn-light" href="'.url('forum/mark-all-as-read').'" data-toggle="tooltip" title="'.$this->lang('Marquer tous les messages comme étant lus').'">'.icon('far fa-eye').'</a>')
							->style('card-transparent text-right');

			$panels->prepend($actions)->append($actions);
		}

		return $panels;
	}

	public function _forum($forum_id, $title, $category_id, $subforums, $announces, $topics)
	{
		$this	->title($title)
				->_breadcrumb($category_id, $forum_id);

		$panels = $this->array;

		if (!empty($subforums))
		{
			$panels->append($this	->panel()
									->body($this->view('index', [
										'title'  => $this->lang('Sous-catégories'),
										'forums' => $subforums
									]), FALSE));
		}

		if (!empty($announces))
		{
			$panels->append($this	->panel()
									->body($this->view('forum', [
										'title'  => $this->lang('Annonces'),
										'icon'   => 'fas fa-flag',
										'topics' => $announces
									]), FALSE));
		}

		$panels->append($this	->panel()
								->body($this->view('forum', [
									'title'  => $title,
									'icon'   => 'fas fa-bars',
									'topics' => $topics
								]), FALSE));

		$content = '<a class="btn btn-light float-left" href="'.url(($this->url->back() ?: 'forum')).'">'.$this->lang('Retour').'</a>';

		if ($pagination = $this->module->pagination->get_pagination())
		{
			$content .= '<div class="float-left ml-1">'.$pagination.'</div>';
		}

		if ($this->access('forum', 'category_write', $category_id))
		{
			$content .= '<a class="float-right btn btn-primary ml-1" href="'.url('forum/new/'.$forum_id.'/'.url_title($title)).'">'.$this->lang('Nouveau sujet').'</a>';
		}

		if ($this->user())
		{
			$content .= '<a class="float-right btn btn-light" href="'.url('forum/mark-all-as-read/'.$forum_id.'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Marquer tous les messages comme étant lus').'">'.icon('far fa-eye').'</a>';
		}

		$actions = $this	->panel()
							->body($content)
							->style('card-transparent');

		$panels->prepend($actions)->append($actions);

		return $panels;
	}

	public function _new($forum_id, $title, $category_id)
	{
		$this	->title($this->lang('Nouveau sujet'))
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb()
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->form()
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
			$this->form()->add_rules([
				'announce' => [
					'type' => 'checkbox'
				]
			]);
		}

		if ($this->form()->is_valid($post))
		{
			$topic_id = $this->model()->add_topic(	$forum_id,
													$post['title'],
													$post['message'],
													!empty($post['announce']) && in_array('on', $post['announce']));

			notify('Sujet ajouté');

			redirect('forum/topic/'.$topic_id.'/'.url_title($post['title']));
		}

		$panels = $this->array;

		if ($errors = $this->form()->get_errors())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Veuillez remplir tous les champs'), 'fas fa-exclamation-triangle')
									->color('danger'));
		}

		$panels->append($this	->panel()
								->heading($this->lang('Nouveau sujet'), 'fas fa-pencil-alt')
								->body($this->view('new', [
									'form_id'     => $this->form()->token(),
									'post'        => $post,
									'forum_id'    => $forum_id,
									'category_id' => $category_id,
									'title'       => $title
								]), FALSE));

		return $panels;
	}

	public function _topic($topic_id, $title, $forum_id, $forum_title, $category_id, $views, $nb_users, $nb_messages, $is_announce, $is_locked, $topic, $messages)
	{
		$this	->title($title)
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb()
				->js('delete');

		$last_message_read = NULL;

		$is_last_page = $nb_messages <= $this->module->pagination->get_items_per_page() || $this->module->pagination->get_page() == ceil($nb_messages / $this->module->pagination->get_items_per_page());

		if ($this->user())
		{
			$last_message_date = $messages ? end($messages)['date'] : $topic['date'];
			$last_message_read = $this->db->select('UNIX_TIMESTAMP(date)')->from('nf_forum_topics_read')->where('user_id', $this->user->id)->where('topic_id', $topic_id)->row();

			$forum_read = $this->db	->select('MAX(UNIX_TIMESTAMP(date))')
									->from('nf_forum_read')
									->where('user_id', $this->user->id)
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
							->where('user_id', $this->user->id)
							->delete('nf_forum_topics_read');

				$this->db->insert('nf_forum_topics_read', [
					'user_id'  => $this->user->id,
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

		$content = '<a class="btn btn-light float-left" href="'.url($this->url->back() ?: 'forum/'.$forum_id.'/'.url_title($forum_title)).'">'.$this->lang('Retour').'</a>';

		if ($pagination = $this->module->pagination->get_pagination())
		{
			$content .= '<div class="float-left ml-1">'.$pagination.'</div>';
		}

		if (!$is_locked && $this->access('forum', 'category_write', $category_id))
		{
			$page = '';

			if ($nb_messages > $this->module->pagination->get_items_per_page() && $this->module->pagination->get_page() != ($last_page = ceil($nb_messages / $this->module->pagination->get_items_per_page())))
			{
				$page = url('forum/topic/'.$topic_id.'/'.url_title($title).'/page/'.$last_page);
			}

			$content .= '<a class="float-right btn btn-primary ml-1" href="'.$page.'#reply">'.$this->lang('Répondre').'</a>';
		}

		if (($this->user() && $topic['user_id'] == $this->user->id) || $this->access('forum', 'category_delete', $category_id))
		{
			$content .= '<a class="float-right btn btn-light delete ml-1" href="'.url('forum/message/delete/'.$topic['message_id'].'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Supprimer le sujet').'">'.icon('fas fa-times').'</a>';
		}

		if ($this->access('forum', 'category_lock', $category_id))
		{
			if ($is_locked)
			{
				$content .= '<a class="float-right btn btn-light ml-1" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Déverrouiller le sujet').'">'.icon('fas fa-unlock').'</a>';
			}
			else
			{
				$content .= '<a class="float-right btn btn-light ml-1" href="'.url('forum/lock/'.$topic_id.'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Verrouiller le sujet').'">'.icon('fas fa-lock').'</a>';
			}
		}

		if ($this->access('forum', 'category_announce', $category_id))
		{
			if ($is_announce)
			{
				$content .= '<a class="float-right btn btn-light ml-1" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Retirer des annonces').'">'.icon('far fa-flag').'</a>';
			}
			else
			{
				$content .= '<a class="float-right btn btn-light ml-1" href="'.url('forum/announce/'.$topic_id.'/'.url_title($title)).'" data-toggle="tooltip" title="'.$this->lang('Mettre en annonce').'">'.icon('fas fa-flag').'</a>';
			}
		}

		if ($this->access('forum', 'category_move', $category_id))
		{
			$content .= '<span class="float-right btn btn-light topic-move" data-toggle="tooltip" data-modal-ajax="'.url('ajax/forum/topic/move/'.$topic_id.'/'.url_title($title)).'" title="'.$this->lang('Déplacer le sujet').'">'.icon('fas fa-reply fa-flip-horizontal').'</span>';
		}

		$panels = $this->array;

		if ($is_locked)
		{
			$panels->append($this	->panel()
									->heading('<a name="reply"></a>'.$this->lang('Le sujet est verrouillé'), 'fas fa-exclamation-triangle')
									->color('danger'));
		}

		$panels->append($this	->panel()
								->body($this->view('topic', array_merge($topic, [
									'category_id'       => $category_id,
									'topic_id'          => $topic_id,
									'title'             => $title,
									'views'             => $views,
									'last_message_read' => $last_message_read
								])), FALSE));

		$actions = $this->panel()
						->body($content)
						->style('card-transparent');

		if (!empty($messages))
		{
			$panels->append($actions);

			$panels->append($this	->panel()
									->body($this->view('messages', [
										'category_id'       => $category_id,
										'topic_id'          => $topic_id,
										'title'             => $title,
										'nb_users'          => $nb_users,
										'nb_messages'       => $nb_messages,
										'messages'          => $messages,
										'last_message_read' => $last_message_read
									]), FALSE));
		}

		$panels->append($actions);

		if ($is_last_page && $this->access('forum', 'category_write', $category_id) && !$is_locked)
		{
			$this	->css('wbbtheme')
					->js('jquery.wysibb.min')
					->js('jquery.wysibb.fr')
					->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
					->form()
					->add_rules([
						'message' => [
							'type'  => 'editor',
							'rules' => 'required'
						]
					])
					->add_submit($this->lang('Répondre'));

			if ($this->form()->is_valid($post))
			{
				$message_id = $this->model()->add_message($topic_id, $post['message']);

				//notify('success', $this->lang('Réponse ajoutée avec succès'));

				$page = '';

				if (++$nb_messages > $this->module->pagination->get_items_per_page())
				{
					$page = '/page/'.ceil($nb_messages / $this->module->pagination->get_items_per_page());
				}

				redirect('forum/topic/'.$topic_id.'/'.url_title($title).$page.'#'.$message_id);
			}

			if ($errors = $this->form()->get_errors())
			{
				$panels->append($this	->panel()
										->heading('<a name="reply"></a>'.$this->lang('Veuillez remplir un message'), 'fas fa-exclamation-triangle')
										->color('danger')
				);
			}

			$panels->append($this	->panel()
									->heading('<a name="reply"></a>'.$this->lang('Répondre au sujet'), 'far fa-file-alt')
									->body($this->view('new', [
										'form_id'  => $this->form()->token()
									]), FALSE));
		}

		return $panels;
	}

	public function _topic_announce($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'status' => (string)($is_announce ? ($is_locked ? -1 : 0) : ($is_locked ? -2 : 1))
					]);
		//notify('success', $this->lang('topic mis en annonce ou pas...'));
		redirect('forum/topic/'.$topic_id.'/'.url_title($title));
	}

	public function _topic_lock($topic_id, $title, $is_announce, $is_locked)
	{
		$this->db	->where('topic_id', $topic_id)
					->update('nf_forum_topics', [
						'status' => (string)($is_locked ? ($is_announce ? 1 : 0) : ($is_announce ? -2 : -1))
					]);
		//notify('success', $this->lang('topic verrouillé ou pas...'));
		redirect('forum/topic/'.$topic_id.'/'.url_title($title));
	}

	public function _message_edit($message_id, $topic_id, $title, $is_topic, $message, $category_id, $forum_id, $user_id, $locked)
	{
		$this	->title($this->lang($is_topic ? 'Édition du sujet' : 'Édition du message'))
				->_breadcrumb($category_id, $forum_id)
				->breadcrumb($title, 'forum/topic/'.$topic_id.'/'.url_title($title))
				->breadcrumb()
				->css('wbbtheme')
				->js('jquery.wysibb.min')
				->js('jquery.wysibb.fr')
				->js_load('$(\'textarea.editor\').wysibb({lang: "fr"});')
				->form()
				->add_rules([
					'message' => [
						'type'  => 'editor',
						'rules' => 'required'
					]
				]);

		if ($is_topic)
		{
			$this->form()->add_rules([
				'title' => [
					'rules' => 'required'
				]
			]);
		}

		if ($this->form()->is_valid($post))
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

			//notify('success', $this->lang('Message modifié avec succès'));

			redirect('forum/topic/'.$topic_id.'/'.url_title($is_topic ? $post['title'] : $title));
		}

		$panels = $this->array;

		if ($errors = $this->form()->get_errors())
		{
			$panels->append($this	->panel()
									->heading($this->lang($is_topic ? 'Veuillez remplir tous les champs' : 'Veuillez remplir un message'), 'fas fa-exclamation-triangle')
									->color('danger')
			);
		}

		$panels->append($this	->panel()
								->heading($this->lang($is_topic ? 'Édition du sujet' : 'Édition du message'), 'far fa-file-alt')
								->body($this->view('new', [
									'form_id'  => $this->form()->token(),
									'post'     => $post,
									'topic_id' => $topic_id,
									'is_topic' => $is_topic,
									'title'    => $title,
									'message'  => $message,
									'user_id'  => $user_id
								]), FALSE));

		return $panels;
	}

	public function _message_delete($message_id, $title, $topic_id, $forum_id, $is_topic)
	{
		$this	->title($this->lang($is_topic ? 'Suppression du topic' : 'Suppression du message'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $is_topic ? $this->lang('Êtes-vous sûr(e) de vouloir supprimer le sujet <b>%s</b> ?', $title) : $this->lang('Êtes-vous sûr(e) de vouloir supprimer ce message ?'));

		if ($this->form()->is_valid())
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
			else if ($this->db->select('message_id')->from('nf_forum_messages')->where('topic_id', $topic_id)->order_by('message_id DESC')->row() == $message_id)
			{
				$this->db	->where('message_id', $message_id)
							->delete('nf_forum_messages');

				$this->db	->where('topic_id', $topic_id)
							->update('nf_forum_topics', 'count_messages = count_messages - 1');

				$this->db	->where('forum_id', $forum_id)
							->update('nf_forum', 'count_messages = count_messages - 1');

				if (($last_message_id = $this->db->select('message_id')->from('nf_forum_messages')->where('topic_id', $topic_id)->order_by('message_id DESC')->row()) &&
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

			if ($is_topic)
			{
				redirect('forum/'.$forum_id.'/'.url_title($this->db()->select('title')->from('nf_forum')->where('forum_id', $forum_id)->row()));
			}
			else
			{
				$nb_messages = $this->db()->from('nf_forum_messages')->where('topic_id', $topic_id)->count() - 1;

				$page = '';

				if ($nb_messages > $this->config->forum_messages_per_page)
				{
					$page = '/page/'.ceil($nb_messages / $this->config->forum_messages_per_page);
				}

				$last_message_id = $this->db()	->select('message_id')
												->from('nf_forum_messages')
												->where('topic_id',     $topic_id)
												->where('message_id <', $message_id)
												->order_by('message_id DESC')
												->row();

				redirect('forum/topic/'.$topic_id.'/'.url_title($title).$page.'#'.$last_message_id);
			}

			return 'OK';
		}

		return $this->form()->display();
	}

	public function mark_all_as_read()
	{
		$this->model()->mark_all_as_read();
		//notify('success', $this->lang('Tous les messages sont désormais considéré comme étant lus'));
		redirect('forum');
	}

	public function _mark_all_as_read($forum_id, $title)
	{
		foreach (array_merge([$forum_id], $this->db->select('forum_id')->from('nf_forum')->where('parent_id', $forum_id)->where('is_subforum', TRUE)->get()) as $id)
		{
			$this->model()->mark_all_as_read($id);
		}

		//notify('success', $this->lang('Tous les messages du forum <b>%s</b> sont désormais considéré comme étant lus', $title));
		redirect('forum/'.$forum_id.'/'.url_title($title));
	}

	private function _breadcrumb($category_id, $forum_id)
	{
		if ($category = $this->db->select('title')->from('nf_forum_categories')->where('category_id', $category_id)->row())
		{
			$this->breadcrumb($category, 'forum');
		}

		if (list($title, $parent_forum_id) = array_values($this->db->select('title', 'IF(is_subforum = "1", parent_id, 0)')->from('nf_forum')->where('forum_id', $forum_id)->row()))
		{
			if ($parent_forum_id && $parent_forum = $this->db->select('title')->from('nf_forum')->where('forum_id', $parent_forum_id)->row())
			{
				$this->breadcrumb($parent_forum, 'forum/'.$parent_forum_id.'/'.url_title($parent_forum));
			}

			$this->breadcrumb($title, 'forum/'.$forum_id.'/'.url_title($title));
		}

		return $this;
	}
}
