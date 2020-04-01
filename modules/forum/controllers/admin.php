<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->subtitle($this->lang('Liste des forums'))
				->css('forum')
				->js('jquery-ui.min')
				->js('forum');

		$this	->add_action('admin/forum/categories/add', $this->lang('Ajouter une catégorie'), 'fas fa-plus')
				->add_action('admin/forum/add',            $this->lang('Ajouter un forum'),    'fas fa-plus');

		$panels = $this->array;

		foreach ($this->model()->get_categories() as $category)
		{
			$panels->append($this	->panel()
									->body($this->view('index', $category), FALSE));
		}

		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('Forum'), 'fas fa-comments')
								->body('<div class="text-center">'.$this->lang('Aucun forum').'</div>')
								->color('info');
		}

		return '<div id="forums-list">'.$panels.'</div>';
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter un forum'))
				->form()
				->add_rules('forum', [
					'categories' => $this->model()->get_categories_list()
				])
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/forum');

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_forum(	$post['title'],
										$post['category'],
										$post['description'],
										$post['url']);

			notify($this->lang('Forum ajouté avec succès'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('Ajouter un forum'), 'fas fa-comments')
					->body($this->form()->display());
	}

	public function _edit($forum_id, $title, $description, $parent_id, $is_subforum, $url)
	{
		$this	->title($this->lang('Édition du forum'))
				->subtitle($title)
				->form()
				->add_rules('forum', [
					'title'        => $title,
					'description'  => $description,
					'category_id'  => ($is_subforum ? 'f' : '').$parent_id,
					'categories'   => $this->model()->get_categories_list($forum_id),
					'url'          => $url
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/forum');

		if ($this->form()->is_valid($post))
		{
			$this->db	->where('forum_id', $forum_id)
						->update('nf_forum', [
							'title'       => $post['title'],
							'parent_id'   => $this->model()->get_parent_id($post['category'], $is_subforum),
							'is_subforum' => $is_subforum,
							'description' => $post['description']
						]);

			if ($post['url'])
			{
				if ($url)
				{
					$this->db	->where('forum_id', $forum_id)
								->update('nf_forum_url', [
									'url' => $post['url']
								]);
				}
				else
				{
					$this->db->insert('nf_forum_url', [
						'forum_id' => $forum_id,
						'url'      => $post['url']
					]);
				}
			}
			else if ($url)
			{
				$this->db	->where('forum_id', $forum_id)
							->delete('nf_forum_url');
			}

			notify($this->lang('Forum édité avec succès'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('Édition du forum'), 'fas fa-comments')
					->body($this->form()->display());
	}

	public function delete($forum_id, $title)
	{
		$this	->title($this->lang('Suppression forum'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le forum <b>%s</b> ?<br />Tous les messages seront aussi supprimés.', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_forum($forum_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _categories_add()
	{
		$this	->subtitle($this->lang('Ajouter une catégorie'))
				->form()
				->add_rules('categories')
				->add_back('admin/forum')
				->add_submit($this->lang('Ajouter'));

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_category($post['title']);

			notify($this->lang('Catégorie ajoutée avec succès'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('Ajouter une catégorie'), 'fas fa-comments')
					->body($this->form()->display());
	}

	public function _categories_edit($category_id, $title)
	{
		$this	->title($this->lang('Édition de la catégorie'))
				->subtitle($title)
				->form()
				->add_rules('categories', [
					'title' => $title
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/forum');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_category($category_id, $post['title']);

			notify($this->lang('Catégorie éditée avec succès'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('Édition de la catégorie'), 'fas fa-comments')
					->body($this->form()->display());
	}

	public function _categories_delete($category_id, $title)
	{
		$this	->title($this->lang('Suppression catégorie'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la catégorie <b>%s</b> ?<br />Toutes les forums et messages associés à cette catégorie seront aussi supprimés.', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
