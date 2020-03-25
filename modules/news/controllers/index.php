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
							->body($this->view('index', $news), FALSE);

			$panels->append($panel);
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Actualités'), 'far fa-file-alt')
									->body('<div class="text-center">'.$this->lang('Aucune actualité n\'a été publiée pour le moment').'</div>')
									->color('info'));
		}

		$panels->append($this->module->pagination->panel());

		return $panels;
	}

	public function _tag($tag, $news)
	{
		$this->subtitle($this->lang('Tag %s', $tag));
		return $this->_filter($news, $this->lang('Tag').' <small>'.$tag.'</small>');
	}

	public function _category($title, $news)
	{
		$this->subtitle($this->lang('Catégorie %s', $title));
		return $this->_filter($news, $this->lang('Catégorie').' <small>'.$title.'</small>');
	}

	private function _filter($news, $filter)
	{
		$news = $this->index($news);

		$news->prepend($this->panel()->body('<h3 class="m-0">'.$filter.$this->button()->tooltip($this->lang('Voir toutes les actualités'))->icon('fas fa-times')->url('news')->color('danger float-right')->compact()->outline().'</h3>'));

		return $news;
	}

	public function _news($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $image, $category_icon, $username, $admin, $online, $quote, $avatar, $sex)
	{
		$this	->title($title)
				->breadcrumb($title);

		$news = $this	->panel()
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
							'sex'            => $sex,
							'next'           => TRUE
						]), FALSE);

		return $this->array
					->append($this->row($this->col($news)))
					->append_if($user_id, $this->row(
												$this->col(
													$this->view('about', [
														'user_id'  => $user_id,
														'username' => $username,
														'avatar'   => $avatar,
														'sex'      => $sex,
														'admin'    => $admin,
														'online'   => $online,
														'quote'    => $quote,
														'news' => $this->model()->get_news_by_user($user_id, $news_id)
													])
												)
										)
					)
					->append_if(($comments = $this->module('comments')) && $comments->is_enabled(), function() use (&$comments, $news_id){
						return $this->row($this->col($comments('news', $news_id)));
					});
	}
}
