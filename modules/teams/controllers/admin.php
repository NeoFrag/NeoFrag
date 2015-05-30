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

class m_teams_c_admin extends Controller_Module
{
	public function index($teams)
	{
		$this	->title('Équipe')
				->subtitle('Liste des équipes')
				->load->library('table');

		$teams = $this	->table
						->add_columns(array(
							array(
								'title'   => 'Équipe',
								'content' => '<a href="{base_url}teams/{team_id}/{name}.html"><img src="{image {icon_id}}" alt="" /> {title}</a>',
								'sort'    => '{title}',
								'search'  => '{title}'
							),
							array(
								'title'   => 'Jeu',
								'content' => '<a href="{base_url}admin/games/{game_id}/{game}.html"><img src="{image {game_icon}}" alt="" /> {game_title}</a>',
								'sort'    => '{game_title}',
								'search'  => '{game_title}'
							),
							array(
								'title'   => '<i class="fa fa-users" data-toggle="tooltip" title="Joueurs"></i>',
								'content' => '{users}',
								'size'    => TRUE
							),
							array(
								'content' => array(
									button_edit('{base_url}admin/teams/{team_id}/{name}.html'),
									button_delete('{base_url}admin/teams/delete/{team_id}/{name}.html')
								),
								'size'    => TRUE
							)
						))
						->sort_by(1, SORT_ASC)
						->data($teams)
						->no_data('Il n\'y a pas encore d\'équipe')
						->display();
			
		$roles = $this	->table
							->add_columns(array(
								array(
									'content' => '<a href="{base_url}admin/teams/roles/{role_id}/{url_title(title)}.html">{title}</a>',
									'search'  => '{title}',
									'sort'    => '{title}'
								),
								array(
									'content' => array(
										button_edit('{base_url}admin/teams/roles/{role_id}/{url_title(title)}.html'),
										button_delete('{base_url}admin/teams/roles/delete/{role_id}/{url_title(title)}.html')
									),
									'size'    => TRUE
								)
							))
							->pagination(FALSE)
							->data($this->model('roles')->get_roles())
							->no_data('Aucun rôle')
							->display();
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'rôles',
					'icon'    => 'fa-sitemap',
					'content' => $roles,
					'footer'  => '<a class="btn btn-outline btn-success" href="{base_url}admin/teams/roles/add.html"><i class="fa fa-plus"></i> Ajouter un rôle</a>',
					'size'    => 'col-md-12 col-lg-4'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Liste des équipes',
					'icon'    => 'fa-gamepad',
					'content' => $teams,
					'footer'  => '<a class="btn btn-outline btn-success" href="{base_url}admin/teams/add.html"><i class="fa fa-plus"></i> Ajouter une équipe</a>',
					'size'    => 'col-md-12 col-lg-8'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle('Ajouter une équipe')
				->load->library('form')
				->add_rules('teams', array(
					'games' => $this->model()->get_games_list()
				))
				->add_submit('Ajouter')
				->add_back('admin/teams.html');

		if ($this->form->is_valid($post))
		{
			$team_id = $this->model()->add_team(	$post['title'],
													$post['game'],
													$post['image'],
													$post['icon'],
													$post['description']);

			add_alert('Succes', 'team ajouté');

			redirect('admin/teams/'.$team_id.'/'.url_title($post['title']).'.html');
		}

		return new Panel(array(
			'title'   => 'Ajouter une équipe',
			'icon'    => 'fa-gamepad',
			'content' => $this->form->display()
		));
	}

	public function _edit($team_id, $name, $title, $image_id, $icon_id, $description, $game_id)
	{
		$users = $this->db	->select('u.user_id', 'u.username', 'tu.user_id IS NOT NULL AS in_team')
							->from('nf_users u')
							->join('nf_teams_users tu', 'tu.user_id = u.user_id AND tu.team_id = '.$team_id)
							->where('u.deleted', FALSE)
							->order_by('u.username')
							->get();
		
		$roles = $this->db	->select('role_id', 'title')
							->from('nf_teams_roles')
							->order_by('title')
							->get();
		
		$form_team = $this	->title('&Eacute;dition')
							->subtitle($title)
							->load->library('form')
							->add_rules('teams', array(
								'title'        => $title,
								'game_id'      => $game_id,
								'games'        => $this->model()->get_games_list(),
								'image_id'     => $image_id,
								'icon_id'      => $icon_id,
								'description'  => $description
							))
							->add_submit('Éditer')
							->add_back('admin/teams.html')
							->save();
		
		$form_users = $this	->form
							->add_rules(array(
								'user_id' => array(
									'type'   => 'select',
									'values' => array_filter(array_map(function($a){
										return !$a['in_team'] ? $a['user_id'] : NULL;
									}, $users)),
									'rules'  => 'required'
								),
								'role_id' => array(
									'type'   => 'select',
									'values' => array_map(function($a){
										return $a['role_id'];
									}, $roles),
									'rules'  => 'required'
								)
							))
							->save();

		if ($form_team->is_valid($post))
		{
			$this->model()->edit_team(	$team_id,
										$post['title'],
										$post['game'],
										$post['image'],
										$post['icon'],
										$post['description']);

			add_alert('Succes', 'team éditée');

			redirect_back('admin/teams.html');
		}
		else if ($form_users->is_valid($post))
		{
			$this->db->insert('nf_teams_users', array(
				'team_id' => $team_id,
				'user_id' => $post['user_id'],
				'role_id' => $post['role_id']
			));
			
			refresh();
		}
		
		$this	->load->library('table')
				->add_columns(array(
					array(
						'content' => function($data){
							return NeoFrag::loader()->user->link($data['user_id'], $data['username']);
						},
					),
					array(
						'content' => '{title}',
					),
					array(
						'content' => array(
							button_delete('{base_url}admin/teams/players/delete/'.$team_id.'/'.$name.'/{user_id}.html')
						),
						'size'    => TRUE
					)
				))
				->pagination(FALSE)
				->data($this->db->select('tu.user_id', 'u.username', 'r.title')->from('nf_teams_users tu')->join('nf_users u', 'u.user_id = tu.user_id')->join('nf_teams_roles r', 'r.role_id = tu.role_id')->where('tu.team_id', $team_id)->order_by('r.title', 'u.username')->get())
				->no_data('Il n\'y a pas encore de joueur dans cette équipe');
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Éditer l\'équipe',
					'icon'    => 'fa-gamepad',
					'content' => $form_team->display(),
					'size'    => 'col-md-12 col-lg-7'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Joueurs',
					'icon'    => 'fa-users',
					'content' => $this->table->display(),
					'footer'  => $this->load->view('users', array(
						'users'   => $users,
						'roles'   => $roles,
						'form_id' => $form_users->id
					)),
					'size'    => 'col-md-12 col-lg-5'
				))
			)
		);
	}

	public function delete($team_id, $title)
	{
		$this	->title('Suppression équipe')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer l\'équipe <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model()->delete_team($team_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _roles_add()
	{
		$this	->subtitle('Ajouter un rôle')
				->load->library('form')
				->add_rules('roles')
				->add_back('admin/teams.html')
				->add_submit('Ajouter');

		if ($this->form->is_valid($post))
		{
			$this->model('roles')->add_role($post['title']);

			add_alert('Succes', 'rôle ajouté');

			redirect_back('admin/teams.html');
		}
		
		return new Panel(array(
			'title'   => 'Ajouter un rôle',
			'icon'    => 'fa-sitemap',
			'content' => $this->form->display()
		));
	}
	
	public function _roles_edit($role_id, $title)
	{
		$this	->subtitle('Rôle '.$title)
				->load->library('form')
				->add_rules('roles', array(
					'title' => $title
				))
				->add_submit('Éditer')
				->add_back('admin/teams.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model('roles')->edit_role($role_id, $post['title']);
		
			add_alert('Succes', 'Rôle édité avec succès');

			redirect_back('admin/teams.html');
		}
		
		return new Panel(array(
			'title'   => 'Éditer le rôle',
			'icon'    => 'fa-sitemap',
			'content' => $this->form->display()
		));
	}
	
	public function _roles_delete($role_id, $title)
	{
		$this	->title('Suppression rôle')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le rôle <b>'.$title.'</b> ?');
				
		if ($this->form->is_valid())
		{
			$this->model('roles')->delete_role($role_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _players_delete($team_id, $user_id, $username)
	{
		$this	->title('Suppression joueur')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le joueur <b>'.$username.'</b> de cette équipe ?');
				
		if ($this->form->is_valid())
		{
			$this->db	->where('team_id', $team_id)
						->where('user_id', $user_id)
						->delete('nf_teams_users');

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./modules/teams/controllers/admin.php
*/