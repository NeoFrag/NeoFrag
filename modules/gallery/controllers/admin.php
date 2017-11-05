<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_gallery_c_admin extends Controller_Module
{
	public function index($gallery)
	{
		$gallery = $this->table
						->add_columns([
							[
								'content' => function($data){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="" style="color: #7bbb17;" data-original-title="'.$this->lang('published').'"></i>' : '<i class="fa fa-eye-slash text-muted" data-toggle="tooltip" title="'.$this->lang('not_published').'"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							],
							[
								'title'   => $this->lang('album'),
								'content' => function($data){
									return $data['published'] ? '<a href="'.url('gallery/album/'.$data['gallery_id'].'/'.$data['name']).'">'.$data['title'].'</a>' : $data['title'];
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
									return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['category_name']).'"><img src="'.path($data['category_icon']).'" alt="" /> '.$data['category_title'].'</a>';
								},
								'sort'    => function($data){
									return $data['category_title'];
								},
								'search'  => function($data){
									return $data['category_title'];
								}
							],
							/* //TODO
							array(
								'title'   => 'Intégration <i class="fa fa-info-circle text-muted" data-toggle="tooltip" title="Code à intégrer pour afficher cette galerie dans un contenu libre de type html/bbcode"></i>',
								'content' => '<code>[gallery-{gallery_id}]</code>'
							),
							*/
							[
								'title'   => '<i class="fa fa-photo" data-toggle="tooltip" title="'.$this->lang('pictures').'"></i>',
								'content' => function($data){
									return $data['images'];
								},
								'sort'    => function($data){
									return $data['images'];
								}
							],
							[
								'content' => [
									function($data){
										return $this->button_update('admin/gallery/'.$data['gallery_id'].'/'.$data['name']);
									},
									function($data){
										return $this->button_delete('admin/gallery/delete/'.$data['gallery_id'].'/'.$data['name']);
									}
								],
								'size'    => TRUE
							]
						])
						->data($gallery)
						->no_data($this->lang('no_photos'))
						->display();

		$categories = $this	->table
							->add_columns([
								[
									'content' => function($data){
										return '<img src="'.path($data['icon_id']).'" alt="" />';
									},
									'size'    => TRUE
								],
								[
									'content' => function($data){
										return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['name']).'">'.$data['title'].'</a>';
									},
									'search'  => function($data){
										return $data['title'];
									}
								],
								[
									'content' => [
										function($data){
											return $this->button_update('admin/gallery/categories/'.$data['category_id'].'/'.$data['name']);
										},
										function($data){
											return $this->button_delete('admin/gallery/categories/delete/'.$data['category_id'].'/'.$data['name']);
										}
									],
									'size'    => TRUE
								]
							])
							->pagination(FALSE)
							->data($this->model()->get_categories())
							->no_data($this->lang('no_category'))
							->display();

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('categories'), 'fa-book')
						->body($categories)
						->footer($this->button_create('admin/gallery/categories/add', $this->lang('add_category')))
						->size('col-md-12 col-lg-4')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('list_album_photos'), 'fa-photo')
						->body($gallery)
						->footer($this->button_create('admin/gallery/add', $this->lang('add_album')))
						->size('col-md-12 col-lg-8')
			)
		);
	}

	public function add()
	{
		$this	->subtitle($this->lang('add_album'))
				->form
				->add_rules('album', [
					'categories' => $this->model()->get_categories_list()
				])
				->add_back('admin/gallery')
				->add_submit($this->lang('create_album_btn'));

		if ($this->form->is_valid($post))
		{
			$gallery_id = $this->model()->add_gallery(	$post['title'],
														$post['category'],
														$post['image'],
														$post['description'],
														in_array('on', $post['published']));

			notify($this->lang('album_added'));

			redirect('admin/gallery/'.$gallery_id.'/'.url_title($post['title']));
		}

		return $this->panel()
					->heading($this->lang('new_photo_album'), 'fa-file-image-o')
					->body($this->form->display());
	}

	public function _edit($gallery_id, $category_id, $image_id, $name, $published, $title, $description, $category_name, $category_title, $category_image, $category_icon)
	{
		$this	->css('dropzone.min')
				->css('admin')
				->js('dropzone')
				->js('admin')
				->js('preview');

		$form_album = $this	->subtitle($title)
							->form
							->add_rules('album', [
								'title'       => $title,
								'category_id' => $category_id,
								'categories'  => $this->model()->get_categories_list(),
								'image'       => $image_id,
								'description' => $description,
								'published'   => $published,
								'gallery_id'  => $gallery_id
							])
							->add_submit($this->lang('edit'))
							->add_back('admin/gallery')
							->save();

		$form_image = $this	->form
							->add_rules([
								'image' => [
									'label'  => $this->lang('image'),
									'type'   => 'file',
									'upload' => 'gallery',
									'info'   => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
									'check'  => function($filename, $ext){
										if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
										{
											return $this->lang('select_image_file');
										}
									},
									'rules'  => 'required'
								],
								'title' => [
									'label' => $this->lang('title'),
									'type'  => 'text'
								],
								'description' => [
									'label' => $this->lang('description'),
									'type'  => 'textarea'
								]
							])
							->add_submit($this->lang('add_image'))
							->save();

		$gallery_table = $this	->table
								->add_columns([
									[
										'content' => function($data){
											return '<a class="thumbnail thumbnail-link" data-toggle="tooltip" title="'.$this->lang('view').'" data-image="'.path($data['file_id']).'" data-title="'.$data['title'].'" data-description="'.$data['description'].'"><img style="max-width: 80px;" src="'.path($data['thumbnail_file_id']).'" alt="" /></a>';
										},
										'size'    => TRUE
									],
									[
										'title'   => $this->lang('title'),
										'content' => function($data){
											return $data['title'];
										},
										'align'   => 'left',
										'sort'    => function($data){
											return $data['title'];
										},
										'search'  => function($data){
											return $data['title'];
										}
									],
									[
										'title'   => $this->lang('date'),
										'content' => function($data){
											return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
										},
										'align'   => 'left',
										'sort'    => function($data){
											return $data['date'];
										},
										'search'  => function($data){
											return $data['date'];
										}
									],
									[
										'content' => [
											function($data){
												return $this->button()
															->tooltip($this->lang('see_image'))
															->icon('fa-eye')
															->url('gallery/image/'.$data['image_id'].'/'.url_title($data['title']))
															->compact()
															->outline();
											},
											function($data){
												return $this->button_update('admin/gallery/image/'.$data['image_id'].'/'.url_title($data['title']));
											},
											function($data){
												return $this->button_delete('admin/gallery/image/delete/'.$data['image_id'].'/'.url_title($data['title']));
											}
										],
										'align'   => 'right',
										'size'    => TRUE
									]
								])
								->data($images = $this->model()->get_images($gallery_id))
								->no_data($this->lang('no_images'))
								->save();

		if ($form_album->is_valid($post))
		{
			$this->model()->edit_gallery(	$gallery_id,
											$post['category'],
											$post['image'],
											in_array('on', $post['published']),
											$post['title'],
											$post['description'],
											$this->config->lang);

			notify($this->lang('album_edited'));

			redirect_back('admin/gallery');
		}
		else if ($form_image->is_valid($post))
		{
			$this->model()->add_image(	$post['image'],
										$gallery_id,
										$post['title'],
										$post['description']);

			notify($this->lang('image_added'));

			refresh();
		}

		return $this->row(
			$this->col(
				$this	->panel()
						->heading(/* //TODO '<div class="pull-right"><code data-toggle="tooltip" title="Code à intégrer pour afficher cette galerie dans un contenu libre de type html/bbcode">[gallery-'.$gallery_id.']</code></div>*/$this->lang('edit_album_title'), 'fa-photo')
						->body($form_album->display())
						->size('col-md-12 col-lg-7')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('add_images_title'), 'fa-photo')
						->body($this->view('upload', [
							'gallery_id' => $gallery_id,
							'name'       => $name,
							'form_image' => $form_image->display()
						]))
						->footer($this->view('admin_gallery', [
							'images'        => $images,
							'gallery_table' => $gallery_table->display()
						]))
						->size('col-md-12 col-lg-5')
			)
		);
	}

	public function delete($gallery_id, $title)
	{
		$this	->title($this->lang('delete_album_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_album_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_gallery($gallery_id);

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _categories_add()
	{
		$this	->subtitle($this->lang('add_category'))
				->form
				->add_rules('categories')
				->add_back('admin/gallery')
				->add_submit($this->lang('add'));

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category(	$post['title'],
											$post['image'],
											$post['icon']);

			notify($this->lang('category_added'));

			redirect_back('admin/gallery');
		}

		return $this->panel()
					->heading($this->lang('add_category'), 'fa-align-left')
					->body($this->form->display());
	}

	public function _categories_edit($category_id, $name, $title, $image_id, $icon_id)
	{
		$this	->subtitle($this->lang('category_', $title))
				->form
				->add_rules('categories', [
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/gallery');

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category(	$category_id,
											$post['title'],
											$post['image'],
											$post['icon']);

			notify($this->lang('category_edited'));

			redirect_back('admin/gallery');
		}

		return $this->panel()
					->heading($this->lang('edit_category_title'), 'fa-align-left')
					->body($this->form->display());
	}

	public function _categories_delete($category_id, $title)
	{
		$this	->title($this->lang('delete_category_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_category_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _image_edit($image_id, $thumbnail_file_id, $title, $description, $gallery_id, $gallery_title)
	{
		$this	->css('admin')
				->js('dropzone')
				->js('admin')
				->js('preview');

		$this	->subtitle($this->lang('image_', $title))
				->form
				->add_rules('image', [
					'image_id'    => $image_id,
					'image'       => $thumbnail_file_id,
					'title'       => $title,
					'description' => $description
				])
				->add_submit($this->lang('edit'))
				->add_back('gallery/'.$gallery_id.'/'.url_title($gallery_title));

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_image(	$image_id,
										$post['title'],
										$post['description']);

			notify($this->lang('image_edited'));

			redirect_back();
		}

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('edit_image_title'), 'fa-photo')
						->body($this->form->display())
						->size('col-md-8 col-lg-9')
			),
			$this->col(
				$this	->panel()
						->heading('<div class="pull-right">'.$this->button_delete('admin/gallery/image/delete/'.$image_id.'/'.url_title($title)).'</div>'.$this->lang('preview_image'), 'fa-photo')
						->body(function($data) use ($image_id, $title, $description, $thumbnail_file_id){
							return '<a class="thumbnail thumbnail-link no-margin" data-toggle="tooltip" title="'.$this->lang('view').'" data-image-id="'.$image_id.'" data-image-title="'.url_title($title).'" data-image-description="'.$description.'"><img src="'.path($thumbnail_file_id).'" alt="" /></a>';
						})
						->size('col-md-4 col-lg-3')
			)
		);
	}

	public function _image_delete($image_id, $title)
	{
		$this	->title($this->lang('delete_image_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_image_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_image($image_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}
