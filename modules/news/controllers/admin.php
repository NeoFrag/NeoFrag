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

class m_news_c_admin extends Controller_Module
{
	public function index($news)
	{
		$this	->title($this('news'))
				->load->library('table');
			
		$news = $this	->table
						->add_columns(array(
							array(
								'content' => function($data, $loader){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$loader->lang('published').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$loader->lang('awaiting_publication').'" style="color: #535353;"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							),
							array(
								'title'   => $this('title'),
								'content' => function($data){
									return '<a href="'.url('news/'.$data['news_id'].'/'.url_title($data['title']).'.html').'">'.$data['title'].'</a>';
								},
								'sort'    => function($data){
									return $data['title'];
								},
								'search'  => function($data){
									return $data['title'];
								}
							),
							array(
								'title'   => $this('category'),
								'content' => function($data){
									return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['category_name'].'.html').'"><img src="'.path($data['category_icon']).'" alt="" /> '.$data['category_title'].'</a>';
								},
								'sort'    => function($data){
									return $data['category_title'];
								},
								'search'  => function($data){
									return $data['category_title'];
								}
							),
							array(
								'title'   => $this('author'),
								'content' => function($data){
									return $data['user_id'] ? NeoFrag::loader()->user->link($data['user_id'], $data['username']) : i18n('guest');
								},
								'sort'    => function($data){
									return $data['username'];
								},
								'search'  => function($data){
									return $data['username'];
								}
							),
							array(
								'title'   => $this('date'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							),
							array(
								'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="'.i18n('comments').'"></i>',
								'content' => function($data){
									return NeoFrag::loader()->library('comments')->admin_comments('news', $data['news_id']);
								},
								'size'    => TRUE
							),
							array(
								'content' => array(
									function($data){
										return button_edit('admin/news/'.$data['news_id'].'/'.url_title($data['title']).'.html');
									},
									function($data){
										return button_delete('admin/news/delete/'.$data['news_id'].'/'.url_title($data['title']).'.html');
									}
								),
								'size'    => TRUE
							)
						))
						->sort_by(5, SORT_DESC, SORT_NUMERIC)
						->data($news)
						->no_data($this('no_news'))
						->display();
			
		$categories = $this	->table
							->add_columns(array(
								array(
									'content' => function($data){
										return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['name'].'.html').'"><img src="'.path($data['icon_id']).'" alt="" /> '.$data['title'].'</a>';
									},
									'search'  => function($data){
										return $data['title'];
									},
									'sort'    => function($data){
										return $data['title'];
									}
								),
								array(
									'content' => array(
										function($data){
											return button_edit('admin/news/categories/'.$data['category_id'].'/'.$data['name'].'.html');
										},
										function($data){
											return button_delete('admin/news/categories/delete/'.$data['category_id'].'/'.$data['name'].'.html');
										}
									),
									'size'    => TRUE
								)
							))
							->pagination(FALSE)
							->data($this->model('categories')->get_categories())
							->no_data($this('no_category'))
							->display();

		return new Row(
			new Col(
				new Panel(array(
					'title'   => $this('categories'),
					'icon'    => 'fa-align-left',
					'content' => $categories,
					'footer'  => button_add('admin/news/categories/add.html', $this('create_category')),
					'size'    => 'col-md-12 col-lg-3'
				))
			),
			new Col(
				new Panel(array(
					'title'   => $this('list_news'),
					'icon'    => 'fa-file-text-o',
					'content' => $news,
					'footer'  => button_add('admin/news/add.html', $this('add_news')),
					'size'    => 'col-md-12 col-lg-9'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle($this('add_news'))
				->load->library('form')
				->add_rules('news', array(
					'categories' => $this->model('categories')->get_categories_list(),
				))
				->add_submit($this('add'))
				->add_back('admin/news.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_news(	$post['title'],
										$post['category'],
										$post['image'],
										$post['introduction'],
										$post['content'],
										$post['tags'],
										in_array('on', $post['published']));

			notify($this('add_news_success_message'));

			redirect_back('admin/news.html');
		}

		return new Panel(array(
			'title'   => $this('add_news'),
			'icon'    => 'fa-file-text-o',
			'content' => $this->form->display()
		));
	}

	public function _edit($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $news_image, $category_image, $category_icon)
	{
		$this	->title($this('edit_news'))
				->subtitle($title)
				->load->library('form')
				->add_rules('news', array(
					'title'        => $title,
					'category_id'  => $category_id,
					'categories'   => $this->model('categories')->get_categories_list(),
					'image_id'     => $image_id,
					'introduction' => $introduction,
					'content'      => $content,
					'tags'         => $tags,
					'published'    => $published
				))
				->add_submit($this('edit'))
				->add_back('admin/news.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_news(	$news_id,
										$post['category'],
										$post['image'],
										in_array('on', $post['published']),
										$post['title'],
										$post['introduction'],
										$post['content'],
										$post['tags'],
										$this->config->lang);

			notify($this('edit_news_success_message'));

			redirect_back('admin/news.html');
		}

		return new Panel(array(
			'title'   => $this('edit_news'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}

	public function delete($news_id, $title)
	{
		$this	->title($this('delete_news'))
				->subtitle($title)
				->load->library('form')
				->confirm_deletion($this('delete_confirmation'), $this('delete_news_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_news($news_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _categories_add()
	{
		$this	->subtitle($this('add_category'))
				->load->library('form')
				->add_rules('categories')
				->add_back('admin/news.html')
				->add_submit($this('add'));

		if ($this->form->is_valid($post))
		{
			$this->model('categories')->add_category(	$post['title'],
														$post['image'],
														$post['icon']);

			notify($this('add_category_success_message'));

			redirect_back('admin/news.html');
		}
		
		return new Panel(array(
			'title'   => $this('add_category'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_edit($category_id, $title, $image_id, $icon_id)
	{
		$this	->subtitle($this('category_', $title))
				->load->library('form')
				->add_rules('categories', array(
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				))
				->add_submit($this('edit'))
				->add_back('admin/news.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model('categories')->edit_category(	$category_id,
														$post['title'],
														$post['image'],
														$post['icon']);
		
			notify($this('edit_category_success_message'));

			redirect_back('admin/news.html');
		}
		
		return new Panel(array(
			'title'   => $this('edit_category'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title($this('delete_category'))
				->subtitle($title)
				->load->library('form')
				->confirm_deletion($this('delete_confirmation'), $this('delete_category_message', $title));
				
		if ($this->form->is_valid())
		{
			$this->model('categories')->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/news/controllers/admin.php
*/