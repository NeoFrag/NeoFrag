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
		$this->load->library('table');
		
		$gallery = $this->table
						->add_columns(array(
							array(
								'content' => function($data){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="" style="color: #7bbb17;" data-original-title="Publiée dans la galerie"></i>' : '<i class="fa fa-eye-slash text-muted" data-toggle="tooltip" title="Non visible dans la galerie"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							),
							array(
								'title'   => 'Album',
								'content' => function($data){
									return $data['published'] ? '<a href="'.url('gallery/album/'.$data['gallery_id'].'/'.$data['name'].'.html').'">'.$data['title'].'</a>' : $data['title'];
								},
								'sort'    => function($data){
									return $data['title'];
								},
								'search'  => function($data){
									return $data['title'];
								}
							),
							array(
								'title'   => 'Catégorie',
								'content' => function($data){
									return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['category_name'].'.html').'"><img src="'.path($data['category_icon']).'" alt="" /> '.$data['category_title'].'</a>';
								},
								'sort'    => function($data){
									return $data['category_title'];
								},
								'search'  => function($data){
									return $data['category_title'];
								}
							),
							/* //TODO
							array(
								'title'   => 'Intégration <i class="fa fa-info-circle text-muted" data-toggle="tooltip" title="Code à intégrer pour afficher cette galerie dans un contenu libre de type html/bbcode"></i>',
								'content' => '<code>[gallery-{gallery_id}]</code>'
							),
							*/
							array(
								'title'   => '<i class="fa fa-photo" data-toggle="tooltip" title="Images"></i>',
								'content' => function($data){
									return $data['images'];
								},
								'sort'    => function($data){
									return $data['images'];
								}
							),
							array(
								'content' => array(
									function($data){
										return button_edit('admin/gallery/'.$data['gallery_id'].'/'.$data['name'].'.html');
									},
									function($data){
										return button_delete('admin/gallery/delete/'.$data['gallery_id'].'/'.$data['name'].'.html');
									}
								),
								'size'    => TRUE
							)
						))
						->data($gallery)
						->no_data('Il n\'y a pas encore d\'album photo')
						->display();
			
		$categories = $this	->table
							->add_columns(array(
								array(
									'content' => function($data){
										return '<img src="'.path($data['icon_id']).'" alt="" />';
									},
									'size'    => TRUE
								),
								array(
									'content' => function($data){
										return '<a href="'.url('admin/gallery/categories/'.$data['category_id'].'/'.$data['name'].'.html').'">'.$data['title'].'</a>';
									},
									'search'  => function($data){
										return $data['title'];
									}
								),
								array(
									'content' => array(
										function($data){
											return button_edit('admin/gallery/categories/'.$data['category_id'].'/'.$data['name'].'.html');
										},
										function($data){
											return button_delete('admin/gallery/categories/delete/'.$data['category_id'].'/'.$data['name'].'.html');
										}
									),
									'size'    => TRUE
								)
							))
							->pagination(FALSE)
							->data($this->model()->get_categories())
							->no_data('Aucune catégorie')
							->display();
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Catégories',
					'icon'    => 'fa-book',
					'content' => $categories,
					'footer'  => button_add('admin/gallery/categories/add.html', 'Ajouter une catégorie'),
					'size'    => 'col-md-12 col-lg-4'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Liste des albums photos',
					'icon'    => 'fa-photo',
					'content' => $gallery,
					'footer'  => button_add('admin/gallery/add.html', 'Créer un album'),
					'size'    => 'col-md-12 col-lg-8'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle('Créer un album')
				->load->library('form')
				->add_rules('album', array(
					'categories' => $this->model()->get_categories_list(),
				))
				->add_back('admin/gallery.html')
				->add_submit('Créer l\'album');
				
		if ($this->form->is_valid($post))
		{
			$gallery_id = $this->model()->add_gallery(	$post['title'],
														$post['category'],
														$post['image'],
														$post['description'],
														in_array('on', $post['published']));

			add_alert('Succes', 'Album ajouté');
			
			redirect('admin/gallery/'.$gallery_id.'/'.url_title($post['title']).'.html');
		}
		
		return new Panel(array(
			'title'   => 'Nouvel album photo',
			'icon'    => 'fa-file-image-o',
			'content' => $this->form->display()
		));
	}
	
	public function _edit($gallery_id, $category_id, $image_id, $name, $published, $title, $description, $category_name, $category_title, $category_image, $category_icon)
	{
		$this	->css('dropzone.min')
				->css('admin')
				->js('dropzone')
				->js('admin')
				->js('preview');
		
		$form_album = $this	->subtitle($title)
							->load->library('form')
							->add_rules('album', array(
								'title'       => $title,
								'category_id' => $category_id,
								'categories'  => $this->model()->get_categories_list(),
								'image'       => $image_id,
								'description' => $description,
								'published'   => $published,
								'gallery_id'  => $gallery_id
							))
							->add_submit('Éditer')
							->add_back('admin/gallery.html')
							->save();
							
		$form_image = $this	->form
							->add_rules($rules = array(
								'image' => array(
									'label'  => 'Image',
									'type'   => 'file',
									'upload' => 'gallery',
									'info'   => ' d\'image (max. '.(file_upload_max_size() / 1024 / 1024).' Mo)',
									'check'  => function($filename, $ext){
										if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
										{
											return 'Veuiller choisir un fichier d\'image';
										}
									},
									'rules'  => 'required'
								),
								'title' => array(
									'label' => 'Titre',
									'type'  => 'text'
								),
								'description' => array(
									'label' => 'Description',
									'type'  => 'textarea'
								)
							))
							->add_submit('Ajouter l\'image')
							->save();
							
		$gallery_table = $this->load->library('table')
									->add_columns(array(
										array(
											'content' => function($data){
												return '<a class="thumbnail thumbnail-link" data-toggle="tooltip" title="Visualiser" data-image="'.path($data['file_id']).'" data-title="'.$data['title'].'" data-description="'.$data['description'].'"><img style="max-width: 80px;" src="'.path($data['thumbnail_file_id']).'" alt="" /></a>';
											},
											'size'    => TRUE
										),
										array(
											'title'   => 'Titre',
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
										),
										array(
											'title'   => 'Date',
											'content' => function($data){
												return '<span data-toggle="tooltip" title="'.timetostr($NeoFrag->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
											},
											'align'   => 'left',
											'sort'    => function($data){
												return $data['date'];
											},
											'search'  => function($data){
												return $data['date'];
											}
										),
										array(
											'content' => array(
												function($data){
													return button('gallery/image/'.$data['image_id'].'/'.url_title($data['title']).'.html', 'fa-eye', 'Voir l\'image');
												},
												function($data){
													return button_edit('admin/gallery/image/'.$data['image_id'].'/'.url_title($data['title']).'.html');
												},
												function($data){
													return button_delete('admin/gallery/image/delete/'.$data['image_id'].'/'.url_title($data['title']).'.html');
												}
											),
											'align'   => 'right',
											'size'    => TRUE
										)
									))
									->data($images = $this->model()->get_images($gallery_id))
									->no_data('Il n\'y a pas encore d\'image')
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

			add_alert('Succes', 'Album édité');

			redirect_back('admin/gallery.html');
		}
		else if ($form_image->is_valid($post))
		{
			$this->model()->add_image(	$post['image'],
										$gallery_id,
										$post['title'],
										$post['description']);
										
			add_alert('Succes', 'Image ajoutée avec succès');

			refresh();
		}

		return new Row(
			new Col(
				new Panel(array(
					'title'   => /* //TODO '<div class="pull-right"><code data-toggle="tooltip" title="Code à intégrer pour afficher cette galerie dans un contenu libre de type html/bbcode">[gallery-'.$gallery_id.']</code></div>*/'Édition de l\'album',
					'icon'    => 'fa-photo',
					'content' => $form_album->display(),
					'size'    => 'col-md-12 col-lg-7'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Ajouter des images',
					'icon'    => 'fa-photo',
					'content' => $this->load->view('upload', array(
						'gallery_id' => $gallery_id,
						'name'       => $name,
						'form_image' => $form_image->display()
					)),
					'footer'  => $this->load->view('admin_gallery', array(
						'images'        => $images,
						'gallery_table' => $gallery_table->display()
					)),
					'size'    => 'col-md-12 col-lg-5'
				))
			)
		);
	}
	
	public function delete($gallery_id, $title)
	{
		$this	->title('Suppression album')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer l\'album <b>'.$title.'</b> ?<br />Toutes les images associées à cet album seront aussi supprimées.');

		if ($this->form->is_valid())
		{
			$this->model()->delete_gallery($gallery_id);

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _categories_add()
	{
		$this	->subtitle('Ajouter une catégorie')
				->load->library('form')
				->add_rules('categories')
				->add_back('admin/gallery.html')
				->add_submit('Ajouter');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category(	$post['title'],
											$post['image'],
											$post['icon']);

			add_alert('Succes', 'Catégorie ajoutée avec succès');

			redirect_back('admin/gallery.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter une catégorie',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_edit($category_id, $name, $title, $image_id, $icon_id)
	{
		$this	->subtitle('Catégorie '.$title)
				->load->library('form')
				->add_rules('categories', array(
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				))
				->add_submit('Éditer')
				->add_back('admin/gallery.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category(	$category_id,
											$post['title'],
											$post['image'],
											$post['icon']);
		
			add_alert('Succes', 'Catégorie éditée avec succès');

			redirect_back('admin/gallery.html');
		}
		
		return new Panel(array(
			'title'   => 'Éditer la catégorie',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title('Suppression catégorie')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la catégorie <b>'.$title.'</b> ?<br />Tous les albums associés à cette catégorie seront aussi supprimés.');
				
		if ($this->form->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _image_add($gallery_id)
	{
		if (!empty($_FILES['file']) && in_array(extension($_FILES['file']['name']), array('gif', 'jpeg', 'jpg', 'png')) && $file_id = $this->load->library('file')->upload($_FILES['file'], 'gallery'))
		{
			$this->model()->add_image($file_id, $gallery_id, basename($_FILES['file']['name']));
		}
		
		exit;
	}
	
	public function _image_edit($image_id, $thumbnail_file_id, $title, $description)
	{
		$this	->css('admin')
				->js('dropzone')
				->js('admin')
				->js('preview');
		
		$this	->subtitle('Image '.$title)
				->load->library('form')
				->add_rules('image', array(
					'image_id'    => $image_id,
					'image'       => $thumbnail_file_id,
					'title'       => $title,
					'description' => $description
				))
				->add_submit('Éditer')
				->add_back();
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_image(	$image_id,
										$post['title'],
										$post['description']);
		
			add_alert('Succes', 'Image éditée avec succès');

			redirect_back();
		}
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Éditer l\'image',
					'icon'    => 'fa-photo',
					'content' => $this->form->display(),
					'size'    => 'col-md-8 col-lg-9'
				))
			),
			new Col(
				new Panel(array(
					'title'   => '<div class="pull-right">'.button_delete('admin/gallery/image/delete/'.$image_id.'/'.url_title($title).'.html').'</div>Aperçu de l\'image',
					'icon'    => 'fa-photo',
					'content' => function($data) use ($image_id, $title, $description){
						return '<a class="thumbnail thumbnail-link no-margin" data-toggle="tooltip" title="Visualiser" data-image-id="'.$image_id.'" data-image-title="'.url_title($title).'" data-image-description="'.$description.'"><img src="'.path($thumbnail_file_id).'" alt="" /></a>';
					},
					'size'    => 'col-md-4 col-lg-3'
				))
			)
		);
	}
	
	public function _image_delete($image_id, $title)
	{
		$this	->title('Suppression image')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer l\'image <b>'.$title.'</b> ?');
				
		if ($this->form->is_valid())
		{
			$this->model()->delete_image($image_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/gallery/controllers/admin.php
*/