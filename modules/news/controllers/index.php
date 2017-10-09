<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index($news)
	{
		$panels = $this->array;

		foreach ($news as $news)
		{
			$news['introduction'] = bbcode($news['introduction']);

			$panel = $this	->panel()
							->heading($news['title'], 'fa-file-text-o', 'news/'.$news['news_id'].'/'.url_title($news['title']))
							->body($this->view('index', $news));

			if ($news['content'])
			{
				$panel->footer('<a href="'.url('news/'.$news['news_id'].'/'.url_title($news['title'])).'">'.$this->lang('Lire la suite').'</a>');
			}

			$panels->append($panel);
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Actualités'), 'fa-file-text-o')
									->body('<div class="text-center">'.$this->lang('Aucune actualité n\'a été publiée pour le moment').'</div>')
									->color('info'));
		}

		$panels->append($this->module->pagination->panel());

		return $panels;
	}

	public function _tag($tag, $news)
	{
		$this->subtitle($this->lang('Tag %s', $tag));
		return $this->_filter($news, $this->lang('Actualités').' <small>'.$tag.'</small>');
	}

	public function _category($title, $news)
	{
		$this->subtitle($this->lang('Catégorie %s', $title));
		return $this->_filter($news, $this->lang('Catégorie d\'actualité').' <small>'.$title.'</small>');
	}

	private function _filter($news, $filter)
	{
		$news = $this->index($news);

		array_unshift($news, $this->panel()->body('<h2 class="m-0">'.$filter.$this->button()->tooltip($this->lang('Voir toutes les actualités'))->icon('fa-close')->url('news')->color('danger pull-right')->compact()->outline().'</h2>'));

		return $news;
	}

	public function _news($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $image, $category_icon, $username, $admin, $online, $quote, $avatar, $sex)
	{
		$this->title($title);

		$news = $this	->panel()
						->heading($title, 'fa-file-text-o')
						->body($this->view('index', [
							'news_id'        => $news_id,
							'category_id'    => $category_id,
							'user_id'        => $user_id,
							'image_id'       => $image_id,
							'date'           => $date,
							'views'          => $views,
							'vote'           => $vote,
							'title'          => $title,
							'introduction'   => bbcode($introduction).'<br /><br />'.bbcode($content),
							'content'        => '',
							'tags'           => $tags,
							'image'          => $image,
							'category_icon'  => $category_icon,
							'category_name'  => $category_name,
							'category_title' => $category_title,
							'username'       => $username,
							'avatar'         => $avatar,
							'sex'            => $sex
						]));

		return $this->array
					->append($this->row($this->col($news)))
					->append_if($user_id, $this->row(
												$this->col(
													$this	->panel()
															->heading($this->lang('À propos de l\'auteur'), 'fa-user')
															->body($this->view('author', [
																'user_id'  => $user_id,
																'username' => $username,
																'avatar'   => $avatar,
																'sex'      => $sex,
																'admin'    => $admin,
																'online'   => $online,
																'quote'    => $quote
															]))
															->size('col-6')
												),
												$this->col(
													$this	->panel()
															->heading($this->lang('Autres actualités de l\'auteur'), 'fa-file-text-o')
															->body($this->view('author_news', [
																'news' => $this->model()->get_news_by_user($user_id, $news_id)
															]), FALSE)
															->size('col-6')
												)
										)
					)
					->append_if(($comments = $this->module('comments')) && $comments->is_enabled(), function() use (&$comments, $news_id){
						return $this->row($this->col($comments('news', $news_id)));
					});
	}
}
