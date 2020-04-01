<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($news)
	{
		$this->title($this->lang('Actualités'));

		$news = $this	->table()
						->add_columns([
							[
								'content' => function($data){
									return $data['published'] ? '<i class="fas fa-circle" data-toggle="tooltip" title="'.$this->lang('Publiée').'" style="color: #7bbb17;"></i>' : '<i class="far fa-circle" data-toggle="tooltip" title="'.$this->lang('En attente de publication').'" style="color: #535353;"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							],
							[
								'title'   => $this->lang('Titre'),
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
								'title'   => $this->lang('Catégorie'),
								'content' => function($data){
									return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['category_name']).'"><img src="'.NeoFrag()->model2('file', $data['category_icon'])->path().'" alt="" /> '.$data['category_title'].'</a>';
								},
								'sort'    => function($data){
									return $data['category_title'];
								},
								'search'  => function($data){
									return $data['category_title'];
								}
							],
							[
								'title'   => $this->lang('Auteur'),
								'content' => function($data){
									return $data['user_id'] ? NeoFrag()->user->link($data['user_id'], $data['username']) : $this->lang('Visiteur');
								},
								'sort'    => function($data){
									return $data['username'];
								},
								'search'  => function($data){
									return $data['username'];
								}
							],
							[
								'title'   => $this->lang('Date'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.time_span($data['date']).'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							],
							[
								'title'   => '<i class="far fa-comments" data-toggle="tooltip" title="'.$this->lang('Commentaires').'"></i>',
								'content' => function($data){
									return $this->module('comments')->admin('news', $data['news_id']);
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
						->no_data($this->lang('Il n\'y a pas encore d\'actualité'))
						->display();

		$categories = $this	->table()
							->add_columns([
								[
									'content' => function($data){
										return '<a href="'.url('admin/news/categories/'.$data['category_id'].'/'.$data['name']).'"><img src="'.NeoFrag()->model2('file', $data['icon_id'])->path().'" alt="" /> '.$data['title'].'</a>';
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
							->no_data($this->lang('Aucune catégorie'))
							->display();

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('Catégories'), 'fas fa-align-left')
						->body($categories)
						->footer_if($this->is_authorized('add_news_category'), $this->button_create('admin/news/categories/add', $this->lang('Créer une catégorie')))
						->size('col-12 col-lg-3')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('Liste des actualités'), 'far fa-file-alt')
						->body($news)
						->footer_if($this->is_authorized('add_news'), $this->button_create('admin/news/add', $this->lang('Ajouter une actualité')))
						->size('col-12 col-lg-9')
			)
		);
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter une actualité'))
				->form()
				->add_rules('news', [
					'categories' => $this->model('categories')->get_categories_list()
				])
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/news');

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_news(	$post['title'],
										$post['category'],
										$post['image'],
										$post['introduction'],
										$post['content'],
										$post['tags'],
										in_array('on', $post['published']));

			notify($this->lang('Actualité ajoutée avec succès'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('Ajouter une actualité'), 'far fa-file-alt')
					->body($this->form()->display());
	}

	public function _edit($news_id, $category_id, $user_id, $image_id, $date, $published, $views, $vote, $title, $introduction, $content, $tags, $category_name, $category_title, $news_image, $category_image, $category_icon)
	{
		$this	->title($this->lang('Éditer l\'actualité'))
				->subtitle($title)
				->form()
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
				->add_submit($this->lang('Éditer'))
				->add_back('admin/news');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_news(	$news_id,
										$post['category'],
										$post['image'],
										in_array('on', $post['published']),
										$post['title'],
										$post['introduction'],
										$post['content'],
										$post['tags'],
										$this->config->lang->info()->name);

			notify($this->lang('Actualité éditée avec succès'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('Éditer l\'actualité'), 'fas fa-align-left')
					->body($this->form()->display());
	}

	public function delete($news_id, $title)
	{
		$this	->title($this->lang('Suppression actualité'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer l\'actualité <b>%s</b> ?<br />Tous les commentaires associés à cette actualité seront aussi supprimés.', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_news($news_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _categories_add()
	{
		$this	->subtitle($this->lang('Ajouter une catégorie'))
				->form()
				->add_rules('categories')
				->add_back('admin/news')
				->add_submit($this->lang('Ajouter'));

		if ($this->form()->is_valid($post))
		{
			$this->model('categories')->add_category(	$post['title'],
														$post['image'],
														$post['icon']);

			notify($this->lang('Catégorie ajoutée avec succès'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('Ajouter une catégorie'), 'fas fa-align-left')
					->body($this->form()->display());
	}

	public function _categories_edit($category_id, $title, $image_id, $icon_id)
	{
		$this	->subtitle($this->lang('Catégorie %s', $title))
				->form()
				->add_rules('categories', [
					'title' => $title,
					'image' => $image_id,
					'icon'  => $icon_id
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/news');

		if ($this->form()->is_valid($post))
		{
			$this->model('categories')->edit_category(	$category_id,
														$post['title'],
														$post['image'],
														$post['icon']);

			notify($this->lang('Catégorie éditée avec succès'));

			redirect_back('admin/news');
		}

		return $this->panel()
					->heading($this->lang('Éditer la catégorie'), 'fas fa-align-left')
					->body($this->form()->display());
	}

	public function _categories_delete($category_id, $title)
	{
		$this	->title($this->lang('Suppression catégorie'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la catégorie <b>%s</b> ?<br />Toutes les actualités associées à cette catégorie seront aussi supprimées.', $title));

		if ($this->form()->is_valid())
		{
			$this->model('categories')->delete_category($category_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
