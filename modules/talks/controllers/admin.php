<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Talks\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($talks)
	{
		$this	->table()
				->add_columns([
					[
						'title'   => $this->lang('Discussion'),
						'content' => function($data){
							return $data['name'];
						},
						'sort'    => function($data){
							return $data['name'];
						},
						'search'  => function($data){
							return $data['name'];
						}
					],
					[
						'content' => [
							function($data){
								if ($data['talk_id'] > 1)
								{
									return $this->button_access($data['talk_id'], 'talk');
								}
							},
							function($data){
								if ($data['talk_id'] > 1)
								{
									return $this->button_update('admin/talks/'.$data['talk_id'].'/'.url_title($data['name']));
								}
							},
							function($data){
								if ($data['talk_id'] > 1)
								{
									return $this->button_delete('admin/talks/delete/'.$data['talk_id'].'/'.url_title($data['name']));
								}
							}
						],
						'size'    => TRUE
					]
				])
				->data($talks)
				->no_data($this->lang('Il n\'y a pas encore de discussion'));

		return $this->panel()
					->heading($this->lang('Liste des discussions'), 'far fa-comment')
					->body($this->table()->display())
					->footer($this->button_create('admin/talks/add', $this->lang('Créer une discussion')));
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter une discussion'))
				->form()
				->add_rules('talks')
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/talks');

		if ($this->form()->is_valid($post))
		{
			$this->model()->add_talk($post['title']);

			notify($this->lang('Discussion ajoutée avec succès'));

			redirect_back('admin/talks');
		}

		return $this->panel()
					->heading($this->lang('Ajouter une discussion'), 'far fa-comment')
					->body($this->form()->display());
	}

	public function _edit($talk_id, $title)
	{
		$this	->subtitle($title)
				->form()
				->add_rules('talks', [
					'title' => $title
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/talks');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_talk($talk_id, $post['title']);

			notify($this->lang('Discussion éditée avec succès'));

			redirect_back('admin/talks');
		}

		return $this->panel()
					->heading($this->lang('Édition de la discussion'), 'far fa-comment')
					->body($this->form()->display());
	}

	public function delete($talk_id, $title)
	{
		$this	->title($this->lang('Suppression d\'une discussion'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la discussion <b>%s</b> ?', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_talk($talk_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
