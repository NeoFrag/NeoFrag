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

class m_news_c_index extends Controller_Module
{
	public function index($news)
	{
		$panels = [];
		
		foreach ($news as $news)
		{
			$news['introduction'] = bbcode($news['introduction']);

			$panel = $this	->panel()
							->heading($news['title'], 'fa-file-text-o', 'news/'.$news['news_id'].'/'.url_title($news['title']).'.html')
							->body($this->view('index', $news));

			if ($news['content'])
			{
				$panel->footer('<a href="'.url('news/'.$news['news_id'].'/'.url_title($news['title']).'.html').'">'.$this->lang('read_more').'</a>');
			}

			$panels[] = $panel;
		}
		
		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('news'), 'fa-file-text-o')
								->body('<div class="text-center">'.$this->lang('no_news_published').'</div>')
								->color('info');
		}

		$panels[] = $this->panel_pagination();

		return $panels;
	}

	public function _tag($tag, $news)
	{
		$this->subtitle($this->lang('tag', $tag));
		return $this->_filter($news, $this->lang('news').' <small>'.$tag.'</small>');
	}
	
	public function _category($title, $news)
	{
		$this->subtitle($this->lang('category_', $title));
		return $this->_filter($news, $this->lang('category_news').' <small>'.$title.'</small>');
	}
	
	private function _filter($news, $filter)
	{
		$news = $this->index($news);
		
		array_unshift($news, $this->panel()->body('<h2 class="no-margin">'.$filter.$this->button()->tooltip($this->lang('show_more'))->icon('fa-close')->url('news.html')->color('danger pull-right')->compact()->outline().'</h2>'));

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
		
		if ($user_id)
		{
			return [
				$this->row($this->col($news)),
				$this->row(
					$this->col(
						$this	->panel()
								->heading($this->lang('about_the_author'), 'fa-user')
								->body($this->view('author', [
									'user_id'  => $user_id,
									'username' => $username,
									'avatar'   => $avatar,
									'sex'      => $sex,
									'admin'    => $admin,
									'online'   => $online,
									'quote'    => $quote
								]))
								->size('col-md-6')
					),
					$this->col(
						$this	->panel()
								->heading($this->lang('more_news_from_author'), 'fa-file-text-o')
								->body($this->view('author_news', [
									'news' => $this->model()->get_news_by_user($user_id, $news_id)
								]), FALSE)
								->size('col-md-6')
					)
				),
				$this->comments->display('news', $news_id)
			];
		}
		else
		{
			return [$news, $this->comments->display('news', $news_id)];
		}
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/news/controllers/index.php
*/