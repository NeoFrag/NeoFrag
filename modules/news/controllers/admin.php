<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_news_c_admin extends Controller_Module
{
	public function index($news)
	{
		$this->title($this->lang('news'));

		$news = $this	->table
						->add_columns([
							[
								'content' => function($data){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$this->lang('published').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$this->lang('awaiting_publication').'" style="color: #535353;"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							],
							[
								'title'   => $this->lang('title'),
								'content' => function($data){
									return '<a href="'.url('news/'.$data['news_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a>';
								},
								'sort'    => function($data){
									return $data['title'];
								},
								'search'  => function($data){
									return $data['title'];
								}
							],
							[
								'title'   => $this->lang('category'),
								'content' => function($data){
									return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['category_name']).'"><img src="'.path($data['category_icon']).'" alt="" /> '.$data['category_title'].'</a>';
								},
								'sort'    => function($data){
									return $data['category_title'];
								},
								'search'  => function($data){
									return $data['category_title'];
								}
							],
							[
								'title'   => $this->lang('author'),
								'content' => function($data){
									return $data['user_id'] ? NeoFrag()->user->link($data['user_id'], $data['username']) : $this->lang('guest');
								},
								'sort'    => function($data){
									return $data['username'];
								},
								'search'  => function($data){
									return $data['username'];
								}
							],
							[
								'title'   => $this->lang('date'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							],
							[
								'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="'.$this->lang('comments').'"></i>',
								'content' => function($data){
									return NeoFrag()->comments->admin_comments('news', $data['news_id']);
								},
								'size'    => TRUE
							],
							[
								'content' => [
									function($data){
										return $this->is_authorized('modify_news') ? $this->button_update('admin/news/'.$data['news_id'].'/'.url_title($data['title'])) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_news') ? $this->button_delete('admin/news/delete/'.$data['news_id'].'/'.url_title($data['title'])) : NULL;
									}
								],
								'size'    => TRUE
							]
						])
						->sort_by(5, SORT_DESC, SORT_NUMERIC)
						->data($news)
						->no_data($this->lang('no_news'))
						->display();
			
		$categories = $this	->table
							->add_columns([
								[
									'content' => function($data){
										return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['name']).'"><img src="'.path($data['icon_id']).'" alt="" /> '.$data['title'].'</a>';
									},
									'search'  => function($data){
										return $data['title'];
									},
									'sort'    => function($data){
										return $data['title'];
									}
								],
								[
									'content' => [
										function($data){
											return $this->is_authorized('modify_news_category') ? $this->button_update('admin/news/categories/'.$data['category_id'].'/'.$data['name']) : NULL;
										},
										function($data){
											return $this->is_authorized('delete_news_category') ? $this->button_delete('admin/news/categories/delete/'.$data['category_id'].'/'.$data['name']) : NULL;
										}
									],
									'size'    => TRUE
								]
							])
							->pagination(FALSE)
							->data($this->model('categories')->get_categories())
							->no_data($this->lang('no_category'))
							->display();

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('categories'), 'fa-align-left')
						->body($categories)
						->footer($this->is_authorized('add_news_category') ? $this->button_create('admin/news/categories/add', $this->lang('create_category')) : NULL)
						->size('col-md-12 col-lg-3')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('list_news'), 'fa-file-text-o')
						->body($news)
						->footer($this->is_authorized('add_news') ? $this->button_create('admin/news/add', $this->lang('add_news')) : NULL)
						->size('col-md-12 col-lg-9')
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle($this->lang('add_news'))
				->form
				->add_rules('news', [
					'categories' => $this->model('categories')->get_categories_list(),
				])
				->add_submit($this->lang('add'))
				->add_back('admin/news');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_news(	$post['title'],
										$post['category'],
										$post['image'],
										$post['introduction'],
										$post['content'],
										$post['tags'],
										in_array('on', $post['published']));

			notify($this->lang('add_news_success_message'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('add_news'), 'fa-file-text-o')
					->body($this->form->display());
	}

	public function _edit($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $news_image, $category_image, $category_icon)
	{
		$this	->title($this->lang('edit_news'))
				->subtitle($title)
				->form
				->add_rules('news', [
					'title'        => $title,
					'category_id'  => $category_id,
					'categories'   => $this->model('categories')->get_categories_list(),
					'image_id'     => $image_id,
					'introduction' => $introduction,
					'content'      => $content,
					'tags'         => $tags,
					'published'    => $published
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/news');

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

			notify($this->lang('edit_news_success_message'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('edit_news'), 'fa-align-left')
					->body($this->form->display());
	}

	public function delete($news_id, $title)
	{
		$this	->title($this->lang('delete_news'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_news_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_news($news_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _categories_add()
	{
		$this	->subtitle($this->lang('add_category'))
				->form
				->add_rules('categories')
				->add_back('admin/news')
				->add_submit($this->lang('add'));

		if ($this->form->is_valid($post))
		{
			$this->model('categories')->add_category(	$post['title'],
														$post['image'],
														$post['icon']);

			notify($this->lang('add_category_success_message'));

			redirect_back('admin/news');
		}
		
		return $this->panel()
					->heading($this->lang('add_category'), 'fa-align-left')
					->body($this->form->display());
	}
	
	public function _categories_edit($category_id, $title, $image_id, $icon_id)
	{
		$this	->subtitle($this->lang('category_', $title))
				->form
				->add_rules('categories', [
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/news');
		
		if ($this->form->is_valid($post))
		{
			$this->model('categories')->edit_category(	$category_id,
														$post['title'],
														$post['image'],
														$post['icon']);
		
			notify($this->lang('edit_category_success_message'));

			redirect_back('admin/news');
		}
		
		return $this->panel()
					->heading($this->lang('edit_category'), 'fa-align-left')
					->body($this->form->display());
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title($this->lang('delete_category'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_category_message', $title));
				
		if ($this->form->is_valid())
		{
			$this->model('categories')->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}
