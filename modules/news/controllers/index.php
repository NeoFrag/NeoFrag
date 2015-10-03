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
		$panels = array();
		
		foreach ($news as $news)
		{
			$news['introduction'] = bbcode($news['introduction']);
			
			$panel = array(
				'title'   => $news['title'],
				'url'     => url('news/'.$news['news_id'].'/'.url_title($news['title']).'.html'),
				'icon'    => 'fa-file-text-o',
				'content' => $this->load->view('index', $news)
			);
			
			if ($news['content'])
			{
				$panel['footer'] = '<a href="'.url('news/'.$news['news_id'].'/'.url_title($news['title']).'.html').'">Lire la suite</a>';
			}
			
			$panels[] = new Panel($panel);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel(array(
				'title'   => 'Actualités',
				'icon'    => 'fa-file-text-o',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">Aucune actualité n\'a été publiée pour le moment</div>'
			));
		}
		else if ($pagination = $this->pagination->get_pagination())
		{
			$panels[] = '<div class="text-right">'.$pagination.'</div>';
		}

		return $panels;
	}

	public function _tag($tag, $news)
	{
		$this->subtitle('Tag '.$tag);
		return $this->_filter($news, 'Actualités <small>'.$tag.'</small>');
	}
	
	public function _category($title, $news)
	{
		$this->subtitle('Catégorie '.$title);
		return $this->_filter($news, 'Catégorie d\'actualité <small>'.$title.'</small>');
	}
	
	private function _filter($news, $filter)
	{
		$news = $this->index($news);
		
		array_unshift($news, new Panel(array(
			'content' => '<h2 class="no-margin">'.$filter.button('news.html', 'fa-close', 'Voir toutes les actualités', 'danger', 'pull-right').'</h2>'
		)));

		return $news;
	}

	public function _news($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $image, $category_icon, $username, $admin, $online, $quote, $avatar, $sex)
	{
		$this->title($title);
		
		return array(
			new Row(
				new Col(
					new Panel(array(
						'title'   => $title,
						'icon'    => 'fa-file-text-o',
						'content' => $this->load->view('index', array(
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
						))
					))
				)
			),
			new Row(
				new Col(
					new Panel(array(
						'title'   => 'À propos de l\'auteur',
						'icon'    => 'fa-user',
						'content' => $this->load->view('author', array(
							'user_id'  => $user_id,
							'username' => $username,
							'avatar'   => $avatar,
							'sex'      => $sex,
							'admin'    => $admin,
							'online'   => $online,
							'quote'    => $quote
						))
					))
					, 'col-md-6'
				),
				new Col(
					new Panel(array(
						'title'   => 'Autre actualités de l\'auteur',
						'icon'    => 'fa-file-text-o',
						'content' => $this->load->view('author_news', array(
							'news' => $this->model()->get_news_by_user($user_id, $news_id)
						)),
						'body'    => FALSE
					))
					, 'col-md-6'
				)
			),
			$this->load->library('comments')->display('news', $news_id)
		);
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/news/controllers/index.php
*/