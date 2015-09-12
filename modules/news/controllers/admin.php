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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_news_c_admin extends Controller_Module
{
	public function index($news)
	{
		$this	->title('Actualités')
				->load->library('table');
			
		$news = $this	->table
						->add_columns(array(
							array(
								'content' => function($data){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="Publiée" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="En attente de publication" style="color: #535353;"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							),
							array(
								'title'   => 'Titre',
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
								'title'   => 'Catégorie',
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
								'title'   => 'Auteur',
								'content' => function($data){
									return NeoFrag::loader()->user->link($data['user_id'], $data['username']);
								},
								'sort'    => function($data){
									return $data['username'];
								},
								'search'  => function($data){
									return $data['username'];
								}
							),
							array(
								'title'   => 'Date',
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							),
							array(
								'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="Commentaires"></i>',
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
						->no_data('Il n\'y a pas encore d\'actualité')
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
							->no_data('Aucune catégorie')
							->display();

		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Catégories',
					'icon'    => 'fa-align-left',
					'content' => $categories,
					'footer'  => '<a class="btn btn-outline btn-success" href="'.url('admin/news/categories/add.html').'">'.icon('fa-plus').' Créer une catégorie</a>',
					'size'    => 'col-md-12 col-lg-3'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Liste des actualités',
					'icon'    => 'fa-file-text-o',
					'content' => $news,
					'footer'  => '<a class="btn btn-outline btn-success" href="'.url('admin/news/add.html').'">'.icon('fa-plus').' Ajouter une actualité</a>',
					'size'    => 'col-md-12 col-lg-9'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle('Ajouter une actualité')
				->load->library('form')
				->add_rules('news', array(
					'categories' => $this->model('categories')->get_categories_list(),
				))
				->add_submit('Ajouter')
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

			add_alert('Succes', 'News ajoutée');

			redirect_back('admin/news.html');
		}

		return new Panel(array(
			'title'   => 'Ajouter une actualité',
			'icon'    => 'fa-file-text-o',
			'content' => $this->form->display()
		));
	}

	public function _edit($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $news_image, $category_image, $category_icon)
	{
		$this	->title('&Eacute;dition')
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
				->add_submit('Éditer')
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

			add_alert('Succes', 'News éditée');

			redirect_back('admin/news.html');
		}

		return new Panel(array(
			'title'   => 'Éditer l\'actualité',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}

	public function delete($news_id, $title)
	{
		$this	->title('Suppression actualité')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer l\'actualité <b>'.$title.'</b> ?<br />Tous les commentaires associés à cette actualité seront aussi supprimés.');

		if ($this->form->is_valid())
		{
			$this->model()->delete_news($news_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _categories_add()
	{
		$this	->subtitle('Ajouter une catégorie')
				->load->library('form')
				->add_rules('categories')
				->add_back('admin/news.html')
				->add_submit('Ajouter');

		if ($this->form->is_valid($post))
		{
			$this->model('categories')->add_category(	$post['title'],
														$post['image'],
														$post['icon']);

			add_alert('Succes', 'Catégorie ajoutée avec succès');

			redirect_back('admin/news.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter une catégorie',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_edit($category_id, $title, $image_id, $icon_id)
	{
		$this	->subtitle('Catégorie '.$title)
				->load->library('form')
				->add_rules('categories', array(
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				))
				->add_submit('Éditer')
				->add_back('admin/news.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model('categories')->edit_category(	$category_id,
														$post['title'],
														$post['image'],
														$post['icon']);
		
			add_alert('Succes', 'Catégorie éditée avec succès');

			redirect_back('admin/news.html');
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
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la catégorie <b>'.$title.'</b> ?<br />Toutes les actualités associées à cette catégorie seront aussi supprimées.');
				
		if ($this->form->is_valid())
		{
			$this->model('categories')->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./modules/news/controllers/admin.php
*/