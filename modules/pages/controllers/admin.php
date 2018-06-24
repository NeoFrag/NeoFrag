<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($pages)
	{
		$this	->table()
				->add_columns([
					[
						'content' => function($data){
							return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$this->lang('Publiée').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$this->lang('En attente de publication').'" style="color: #535353;"></i>';
						},
						'sort'    => function($data){
							return $data['published'];
						},
						'size'    => TRUE
					],
					[
						'title'   => $this->lang('Titre de la page'),
						'content' => function($data){
							return $data['published'] ? '<a href="'.url($data['name']).'">'.$data['title'].'</a> <small class="text-muted">'.$data['subtitle'].'</small>' : $data['title'];
						},
						'sort'    => function($data){
							return $data['title'];
						},
						'search'  => function($data){
							return $data['title'];
						}
					],
					[
						'content' => [
							function($data){
								return $data['published'] ? $this->button()->tooltip($this->lang('Voir la page'))->icon('fa-eye')->url($data['name'])->compact()->outline() : '';
							},
							function($data){
								return $this->button_update('admin/pages/'.$data['page_id'].'/'.url_title($data['title']));
							},
							function($data){
								return $this->button_delete('admin/pages/delete/'.$data['page_id'].'/'.url_title($data['title']));
							}
						],
						'size'    => TRUE
					]
				])
				->data($pages)
				->no_data($this->lang('Il n\'y a pas encore de page'));

		return $this->panel()
					->heading($this->lang('Liste des pages'), 'fa-align-left')
					->body($this->table()->display())
					->footer($this->button_create('admin/pages/add', $this->lang('Créer une page')));
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter une page'))
				->form()
				->add_rules('pages')
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/pages');

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_page(	$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content']);

			notify($this->lang('Page ajoutée avec succès'));

			redirect_back('admin/pages');
		}

		return $this->panel()
					->heading($this->lang('Ajouter une page'), 'fa-align-left')
					->body($this->form()->display());
	}

	public function _edit($page_id, $name, $published, $title, $subtitle, $content, $tab)
	{
		$this	->subtitle($title)
				->form()
				->add_rules('pages', [
					'title'          => $title,
					'subtitle'       => $subtitle,
					'name'           => $name,
					'content'        => $content,
					'published'      => $published
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/pages');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_page(	$page_id,
										$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content'],
										$this->config->lang);

			notify($this->lang('Page éditée avec succès'));

			redirect_back('admin/pages');
		}

		return $this->panel()
					->heading($this->lang('Édition de la page'), 'fa-align-left')
					->body($this->form()->display());
	}

	public function delete($page_id, $title)
	{
		$this	->title($this->lang('Suppression d\'une page'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la page <b>%s</b> ?', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_page($page_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
