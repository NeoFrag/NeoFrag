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

class m_talks_c_admin extends Controller_Module
{
	public function index($talks)
	{
		$this	->load->library('table')
				->add_columns(array(
					array(
						'content' => function($data){
							return NeoFrag::loader()->assets->icon(NeoFrag::loader()->db->select('entity_id')->from('nf_permissions p')->join('nf_permissions_details d', 'p.permission_id = d.permission_id')->where('addon_id', $data['talk_id'])->where('addon', 'talks')->where('action', 'write')->row() == 'admins' ? 'fa-lock' : 'fa-unlock');
						},
						'size'    => TRUE
					),
					array(
						'title'   => 'Discussion',
						'content' => '{name}',
						'sort'    => '{name}',
						'search'  => '{name}'
					),
					array(
						'content' => array(
							function($data){
								if ($data['talk_id'] > 1)
								{
									return button_edit('{base_url}admin/talks/{talk_id}/{url_title(name)}.html');
								}
							},
							function($data){
								if ($data['talk_id'] > 1)
								{
									return button_delete('{base_url}admin/talks/delete/{talk_id}/{url_title(name)}.html');
								}
							}
						),
						'size'    => TRUE
					)
				))
				->data($talks)
				->no_data('Il n\'y a pas encore de discussion');
						
		return new Panel(array(
			'title'   => 'Liste des discussions',
			'icon'    => 'fa-comment-o',
			'content' => $this->table->display(),
			'footer'  => button_add('{base_url}admin/talks/add.html', 'Créer une discussion')
		));
	}
	
	public function add()
	{
		$this	->subtitle('Ajouter une discussion')
				->load->library('form')
				->add_rules('talks')
				->add_submit('Ajouter')
				->add_back('admin/talks.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_talk($post['title'], in_array('on', $post['private']));
			
			add_alert('Succes', 'Discussion ajoutée avec succès');

			redirect_back('admin/talks.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter une discussion',
			'icon'    => 'fa-comment-o',
			'content' => $this->form->display()
		));
	}

	public function _edit($talk_id, $title)
	{
		$this	->subtitle($title)
				->load->library('form')
				->add_rules('talks', array(
					'title'   => $title,
					'private' => $this->db->select('entity_id')->from('nf_permissions p')->join('nf_permissions_details d', 'p.permission_id = d.permission_id')->where('addon_id', $talk_id)->where('addon', 'talks')->where('action', 'write')->row() == 'admins'
				))
				->add_submit('Éditer')
				->add_back('admin/talks.html');
		
		if ($this->form->is_valid($post))
		{	
			$this->model()->edit_talk(	$talk_id,
										$post['title'],
										in_array('on', $post['private']));
		
			add_alert('Succes', 'Discussion éditée avec succès');

			redirect_back('admin/talks.html');
		}
		
		return new Panel(array(
			'title'   => 'Édition de la discussion',
			'icon'    => 'fa-comment-o',
			'content' => $this->form->display()
		));
	}
	
	public function delete($talk_id, $title)
	{
		$this	->title('Suppression d\'une discussion')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la discussion <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model()->delete_talk($talk_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./modules/talks/controllers/admin.php
*/