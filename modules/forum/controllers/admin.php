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
 
class m_forum_c_admin extends Controller_Module
{
	public function index()
	{
		$this	->subtitle('Liste des forums')
				->js('forum')
				->add_action('{base_url}admin/forum/categories/add.html', 'Ajouter une catégorie', 'fa-plus')
				->add_action('{base_url}admin/forum/add.html', 'Ajouter un forum', 'fa-plus');
		
		$panels = array();
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = new Panel(array(
				'content' => $this->load->view('index', $category),
				'body'    => FALSE
			));
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel(array(
				'title'   => 'Forum',
				'icon'    => 'fa-comments',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">Il n\'y a pas de forum pour le moment</div>'
			));
		}

		return '<div id="forums-list">'.display($panels).'</div>';
	}
	
	public function add()
	{
		$this	->subtitle('Ajouter un forum')
				->load->library('form')
				->add_rules('forum', array(
					'categories' => $this->model()->get_categories_list(),
				))
				->add_submit('Ajouter')
				->add_back('admin/forum.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_forum(	$post['title'],
										$post['category'],
										$post['description']);

			add_alert('Succes', 'Forum ajouté');

			redirect_back('admin/forum.html');
		}

		return new Panel(array(
			'title'   => 'Ajouter un forum',
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		));
	}

	public function _edit($forum_id, $title, $description, $url, $category_id, $category_title)
	{
		$this	->title('&Eacute;dition')
				->subtitle($title)
				->load->library('form')
				->add_rules('forum', array(
					'title'        => $title,
					'description'  => $description,
					'category_id'  => $category_id,
					'categories'   => $this->model()->get_categories_list()
				))
				->add_submit('Éditer')
				->add_back('admin/forum.html');

		if ($this->form->is_valid($post))
		{
			$this->db	->where('forum_id', $forum_id)
						->update('nf_forum', array(
							'title'       => $post['title'],
							'parent_id'   => $post['category'],
							'description' => $post['description']
						));

			add_alert('Succes', 'Forum édité');

			redirect_back('admin/forum.html');
		}

		return new Panel(array(
			'title'   => 'Éditer le forum',
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		));
	}

	public function delete($forum_id, $title)
	{
		$this	->title('Suppression forum')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le forum <b>'.$title.'</b> ?<br />Tous les messages seront aussi supprimés.');

		if ($this->form->is_valid())
		{
			$this->model()->delete_forum($forum_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _categories_add()
	{
		$this	->subtitle('Ajouter une catégorie')
				->load->library('form')
				->add_rules('categories')
				->add_back('admin/forum.html')
				->add_submit('Ajouter');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category(	$post['title'],
											in_array('on', $post['private']));

			add_alert('Succes', 'Catégorie ajoutée avec succès');

			redirect_back('admin/forum.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter une catégorie',
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_edit($category_id, $title)
	{
		$this	->subtitle('Catégorie '.$title)
				->load->library('form')
				->add_rules('categories', array(
					'title'   => $title,
					'private' => $this->db->select('entity_id')->from('nf_permissions p')->join('nf_permissions_details d', 'p.permission_id = d.permission_id')->where('addon_id', $category_id)->where('addon', 'forum')->where('action', 'category_read')->row() == $this->groups()['admins']['id']
				))
				->add_submit('Éditer')
				->add_back('admin/forum.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category(	$category_id,
											$post['title'],
											in_array('on', $post['private']));
		
			add_alert('Succes', 'Catégorie éditée avec succès');

			redirect_back('admin/forum.html');
		}
		
		return new Panel(array(
			'title'   => 'Éditer la catégorie',
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		));
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title('Suppression catégorie')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la catégorie <b>'.$title.'</b> ?<br />Toutes les forums et messages associés à cette catégorie seront aussi supprimés.');
				
		if ($this->form->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./modules/forum/controllers/admin.php
*/