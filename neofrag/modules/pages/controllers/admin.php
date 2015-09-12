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

class m_pages_c_admin extends Controller_Module
{
	public function index($pages)
	{
		$this	->load->library('table')
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
						'title'   => 'Titre de la page',
						'content' => function($data){
							return '<a href="'.url($data['name'].'.html').'">'.$data['title'].'</a> <small class="text-muted">'.$data['subtitle'].'</small>';
						},
						'sort'    => function($data){
							return $data['title'];
						},
						'search'  => function($data){
							return $data['title'];
						}
					),
					array(
						'content' => array(
							function($adta){
								return button($data['name'].'.html', 'fa-eye', 'Voir la page');
							},
							function($data){
								return button_edit('admin/pages/'.$data['page_id'].'/'.url_title($data['title']).'.html');
							},
							function($data){
								return button_delete('admin/pages/delete/'.$data['page_id'].'/'.url_title($data['title']).'.html');
							}
						),
						'size'    => TRUE
					)
				))
				->data($pages)
				->no_data('Il n\'y a pas encore de page');
						
		return new Panel(array(
			'title'   => 'Liste des pages',
			'icon'    => 'fa-align-left',
			'content' => $this->table->display(),
			'footer'  => button_add('admin/pages/add.html', 'Créer une page')
		));
	}
	
	public function add()
	{
		$this	->subtitle('Ajouter une page')
				->load->library('form')
				->add_rules('pages')
				->add_submit('Ajouter')
				->add_back('admin/pages.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_page(	$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content']);

			add_alert('Succes', 'Page ajoutée avec succès');

			redirect_back('admin/pages.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter une page',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}

	public function _edit($page_id, $name, $published, $title, $subtitle, $content, $tab)
	{
		$this	->subtitle($title)
				->load->library('form')
				->add_rules('pages', array(
					'title'          => $title,
					'subtitle'       => $subtitle,
					'name'           => $name,
					'content'        => $content,
					'published'      => $published
				))
				->add_submit('Éditer')
				->add_back('admin/pages.html');
		
		if ($this->form->is_valid($post))
		{	
			$this->model()->edit_page(	$page_id,
										$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content'],
										$this->config->lang);
		
			add_alert('Succes', 'Page éditée avec succès');

			redirect_back('admin/pages.html');
		}
		
		return new Panel(array(
			'title'   => 'Édition de la page',
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		));
	}
	
	public function delete($page_id, $title)
	{
		$this	->title('Suppression d\'une page')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la page <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model()->delete_page($page_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/pages/controllers/admin.php
*/