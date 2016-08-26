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

class m_gallery_c_admin extends Controller_Module
{
	public function index($gallery)
	{
		$gallery = $this->table
						->add_columns([
							[
								'content' => function($data, $loader){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="" style="color: #7bbb17;" data-original-title="'.$loader->lang('published').'"></i>' : '<i class="fa fa-eye-slash text-muted" data-toggle="tooltip" title="'.$loader->lang('not_published').'"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							],
							[
								'title'   => $this('album'),
								'content' => function($data){
									return $data['published'] ? '<a href="'.url('gallery/album/'.$data['gallery_id'].'/'.$data['name'].'.html').'">'.$data['title'].'</a>' : $data['title'];
								},
								'sort'    => function($data){
									return $data['title'];
								},
								'search'  => function($data){
									return $data['title'];
								}
							],
							[
								'title'   => $this('category'),
								'content' => function($data){
									return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['category_name'].'.html').'"><img src="'.path($data['category_icon']).'" alt="" /> '.$data['category_title'].'</a>';
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
								'title'   => '<i class="fa fa-photo" data-toggle="tooltip" title="'.$this('pictures').'"></i>',
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
										return button_edit('admin/gallery/'.$data['gallery_id'].'/'.$data['name'].'.html');
									},
									function($data){
										return button_delete('admin/gallery/delete/'.$data['gallery_id'].'/'.$data['name'].'.html');
									}
								],
								'size'    => TRUE
							]
						])
						->data($gallery)
						->no_data($this('no_photos'))
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
										return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['name'].'.html').'">'.$data['title'].'</a>';
									},
									'search'  => function($data){
										return $data['title'];
									}
								],
								[
									'content' => [
										function($data){
											return button_edit('admin/gallery/categories/'.$data['category_id'].'/'.$data['name'].'.html');
										},
										function($data){
											return button_delete('admin/gallery/categories/delete/'.$data['category_id'].'/'.$data['name'].'.html');
										}
									],
									'size'    => TRUE
								]
							])
							->pagination(FALSE)
							->data($this->model()->get_categories())
							->no_data($this('no_category'))
							->display();
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('categories'),
					'icon'    => 'fa-book',
					'content' => $categories,
					'footer'  => button_add('admin/gallery/categories/add.html', $this('add_category')),
					'size'    => 'col-md-12 col-lg-4'
				])
			),
			new Col(
				new Panel([
					'title'   => $this('list_album_photos'),
					'icon'    => 'fa-photo',
					'content' => $gallery,
					'footer'  => button_add('admin/gallery/add.html', $this('add_album')),
					'size'    => 'col-md-12 col-lg-8'
				])
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle($this('add_album'))
				->form
				->add_rules('album', [
					'categories' => $this->model()->get_categories_list(),
				])
				->add_back('admin/gallery.html')
				->add_submit($this('create_album_btn'));
				
		if ($this->form->is_valid($post))
		{
			$gallery_id = $this->model()->add_gallery(	$post['title'],
														$post['category'],
														$post['image'],
														$post['description'],
														in_array('on', $post['published']));

			notify($this('album_added'));
			
			redirect('admin/gallery/'.$gallery_id.'/'.url_title($post['title']).'.html');
		}
		
		return new Panel([
			'title'   => $this('new_photo_album'),
			'icon'    => 'fa-file-image-o',
			'content' => $this->form->display()
		]);
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
							->add_submit($this('edit'))
							->add_back('admin/gallery.html')
							->save();
							
		$form_image = $this	->form
							->add_rules([
								'image' => [
									'label'  => $this('image'),
									'type'   => 'file',
									'upload' => 'gallery',
									'info'   => $this('file_picture', file_upload_max_size() / 1024 / 1024),
									'check'  => function($filename, $ext){
										if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
										{
											return i18n('select_image_file');
										}
									},
									'rules'  => 'required'
								],
								'title' => [
									'label' => $this('title'),
									'type'  => 'text'
								],
								'description' => [
									'label' => $this('description'),
									'type'  => 'textarea'
								]
							])
							->add_submit($this('add_image'))
							->save();
							
		$gallery_table = $this	->table
								->add_columns([
									[
										'content' => function($data, $loader){
											return '<a class="thumbnail thumbnail-link" data-toggle="tooltip" title="'.$loader->lang('view').'" data-image="'.path($data['file_id']).'" data-title="'.$data['title'].'" data-description="'.$data['description'].'"><img style="max-width: 80px;" src="'.path($data['thumbnail_file_id']).'" alt="" /></a>';
										},
										'size'    => TRUE
									],
									[
										'title'   => $this('title'),
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
										'title'   => $this('date'),
										'content' => function($data){
											return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
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
											function($data, $loader){
												return button('gallery/image/'.$data['image_id'].'/'.url_title($data['title']).'.html', 'fa-eye', $loader->lang('see_image'));
											},
											function($data){
												return button_edit('admin/gallery/image/'.$data['image_id'].'/'.url_title($data['title']).'.html');
											},
											function($data){
												return button_delete('admin/gallery/image/delete/'.$data['image_id'].'/'.url_title($data['title']).'.html');
											}
										],
										'align'   => 'right',
										'size'    => TRUE
									]
								])
								->data($images = $this->model()->get_images($gallery_id))
								->no_data($this('no_images'))
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

			notify($this('album_edited'));

			redirect_back('admin/gallery.html');
		}
		else if ($form_image->is_valid($post))
		{
			$this->model()->add_image(	$post['image'],
										$gallery_id,
										$post['title'],
										$post['description']);
										
			notify($this('image_added'));

			refresh();
		}

		return new Row(
			new Col(
				new Panel([
					'title'   => /* //TODO '<div class="pull-right"><code data-toggle="tooltip" title="Code à intégrer pour afficher cette galerie dans un contenu libre de type html/bbcode">[gallery-'.$gallery_id.']</code></div>*/$this('edit_album_title'),
					'icon'    => 'fa-photo',
					'content' => $form_album->display(),
					'size'    => 'col-md-12 col-lg-7'
				])
			),
			new Col(
				new Panel([
					'title'   => $this('add_images_title'),
					'icon'    => 'fa-photo',
					'content' => $this->load->view('upload', [
						'gallery_id' => $gallery_id,
						'name'       => $name,
						'form_image' => $form_image->display()
					]),
					'footer'  => $this->load->view('admin_gallery', [
						'images'        => $images,
						'gallery_table' => $gallery_table->display()
					]),
					'size'    => 'col-md-12 col-lg-5'
				])
			)
		);
	}
	
	public function delete($gallery_id, $title)
	{
		$this	->title($this('delete_album_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_album_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_gallery($gallery_id);

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _categories_add()
	{
		$this	->subtitle($this('add_category'))
				->form
				->add_rules('categories')
				->add_back('admin/gallery.html')
				->add_submit($this('add'));

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category(	$post['title'],
											$post['image'],
											$post['icon']);

			notify($this('category_added'));

			redirect_back('admin/gallery.html');
		}
		
		return new Panel([
			'title'   => $this('add_category'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		]);
	}
	
	public function _categories_edit($category_id, $name, $title, $image_id, $icon_id)
	{
		$this	->subtitle($this('category_', $title))
				->form
				->add_rules('categories', [
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				])
				->add_submit($this('edit'))
				->add_back('admin/gallery.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category(	$category_id,
											$post['title'],
											$post['image'],
											$post['icon']);
		
			notify($this('category_edited'));

			redirect_back('admin/gallery.html');
		}
		
		return new Panel([
			'title'   => $this('edit_category_title'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		]);
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title($this('delete_category_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_category_message', $title));
				
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
		
		$this	->subtitle($this('image_', $title))
				->form
				->add_rules('image', [
					'image_id'    => $image_id,
					'image'       => $thumbnail_file_id,
					'title'       => $title,
					'description' => $description
				])
				->add_submit($this('edit'))
				->add_back('gallery/'.$gallery_id.'/'.url_title($gallery_title).'.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_image(	$image_id,
										$post['title'],
										$post['description']);
		
			notify($this('image_edited'));

			redirect_back();
		}
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('edit_image_title'),
					'icon'    => 'fa-photo',
					'content' => $this->form->display(),
					'size'    => 'col-md-8 col-lg-9'
				])
			),
			new Col(
				new Panel([
					'title'   => '<div class="pull-right">'.button_delete('admin/gallery/image/delete/'.$image_id.'/'.url_title($title).'.html').'</div>'.$this('preview_image'),
					'icon'    => 'fa-photo',
					'content' => function($data, $loader) use ($image_id, $title, $description, $thumbnail_file_id){
						return '<a class="thumbnail thumbnail-link no-margin" data-toggle="tooltip" title="'.$loader->lang('view').'" data-image-id="'.$image_id.'" data-image-title="'.url_title($title).'" data-image-description="'.$description.'"><img src="'.path($thumbnail_file_id).'" alt="" /></a>';
					},
					'size'    => 'col-md-4 col-lg-3'
				])
			)
		);
	}
	
	public function _image_delete($image_id, $title)
	{
		$this	->title($this('delete_image_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_image_message', $title));
				
		if ($this->form->is_valid())
		{
			$this->model()->delete_image($image_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/gallery/controllers/admin.php
*/