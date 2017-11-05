<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_teams_c_admin extends Controller_Module
{
	public function index()
	{
		$this->subtitle($this->lang('list_teams'));

		$teams = $this	->table
						->add_columns([
							[
								'content' => function($data){
									return $this->button_sort($data['team_id'], 'admin/ajax/teams/sort');
								},
								'size'    => TRUE
							],
							[
								'title'   => $this->lang('teams'),
								'content' => function($data){
									return '<a href="'.url('teams/'.$data['team_id'].'/'.$data['name']).'"><img src="'.path($data['icon_id']).'" alt="" /> '.$data['title'].'</a>';
								}
							],
							[
								'title'   => $this->lang('game'),
								'content' => function($data){
									return '<a href="'.url('admin/games/'.$data['team_id'].'/'.$data['game']).'"><img src="'.path($data['game_icon']).'" alt="" /> '.$data['game_title'].'</a>';
								}
							],
							[
								'title'   => '<i class="fa fa-users" data-toggle="tooltip" title="'.$this->lang('players').'"></i>',
								'content' => function($data){
									return $data['users'];
								},
								'size'    => TRUE
							],
							[
								'content' => [
									function($data){
										return $this->button_update('admin/teams/'.$data['team_id'].'/'.$data['name']);
									},
									function($data){
										return $this->button_delete('admin/teams/delete/'.$data['team_id'].'/'.$data['name']);
									}
								],
								'size'    => TRUE
							]
						])
						->data($this->model()->get_teams())
						->no_data($this->lang('no_team'))
						->display();
			
		$roles = $this	->table
							->add_columns([
								[
									'content' => function($data){
										return $this->button_sort($data['role_id'], 'admin/ajax/teams/roles/sort');
									},
									'size'    => TRUE
								],
								[
									'content' => function($data){
										return '<a href="'.url('admin/teams/roles/'.$data['role_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a>';
									}
								],
								[
									'content' => [
										function($data){
											return $this->button_update('admin/teams/roles/'.$data['role_id'].'/'.url_title($data['title']));
										},
										function($data){
											return $this->button_delete('admin/teams/roles/delete/'.$data['role_id'].'/'.url_title($data['title']));
										}
									],
									'size'    => TRUE
								]
							])
							->pagination(FALSE)
							->data($this->model('roles')->get_roles())
							->no_data($this->lang('no_role'))
							->display();
		
		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('roles'), 'fa-sitemap')
						->body($roles)
						->footer($this->button_create('admin/teams/roles/add', $this->lang('add_role')))
						->size('col-md-12 col-lg-4')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('list_teams'), 'fa-gamepad')
						->body($teams)
						->footer($this->button_create('admin/teams/add', $this->lang('add_team')))
						->size('col-md-12 col-lg-8')
			)
		);
	}
	
	public function add()
	{
		$this	->subtitle($this->lang('add_team'))
				->form
				->add_rules('teams', [
					'games' => $this->model()->get_games_list()
				])
				->add_submit($this->lang('add'))
				->add_back('admin/teams');

		if ($this->form->is_valid($post))
		{
			$team_id = $this->model()->add_team(	$post['title'],
													$post['game'],
													$post['image'],
													$post['icon'],
													$post['description']);

			notify($this->lang('add_team_success_message'));

			redirect('admin/teams/'.$team_id.'/'.url_title($post['title']));
		}

		return $this->panel()
					->heading($this->lang('add_team'), 'fa-gamepad')
					->body($this->form->display());
	}

	public function _edit($team_id, $name, $title, $image_id, $icon_id, $description, $game_id)
	{
		$users = $this->db	->select('u.user_id', 'u.username', 'tu.user_id IS NOT NULL AS in_team')
							->from('nf_users u')
							->join('nf_teams_users tu', 'tu.user_id = u.user_id AND tu.team_id = '.$team_id)
							->join('nf_teams_roles r',  'r.role_id  = tu.role_id')
							->where('u.deleted', FALSE)
							->order_by('r.order', 'r.role_id', 'u.username')
							->get();
		
		$roles = $this->model('roles')->get_roles();
		
		$form_team = $this	->title($this->lang('edit_team'))
							->subtitle($title)
							->form
							->add_rules('teams', [
								'title'        => $title,
								'game_id'      => $game_id,
								'games'        => $this->model()->get_games_list(),
								'image_id'     => $image_id,
								'icon_id'      => $icon_id,
								'description'  => $description
							])
							->add_submit($this->lang('edit'))
							->add_back('admin/teams')
							->save();
		
		$form_users = $this	->form
							->add_rules([
								'user_id' => [
									'type'   => 'select',
									'values' => array_filter(array_map(function($a){
										return !$a['in_team'] ? $a['user_id'] : NULL;
									}, $users)),
									'rules'  => 'required'
								],
								'role_id' => [
									'type'   => 'select',
									'values' => array_map(function($a){
										return $a['role_id'];
									}, $roles),
									'rules'  => 'required'
								]
							])
							->save();

		if ($form_team->is_valid($post))
		{
			$this->model()->edit_team(	$team_id,
										$post['title'],
										$post['game'],
										$post['image'],
										$post['icon'],
										$post['description']);

			notify($this->lang('edit_team_success_message'));

			redirect_back('admin/teams');
		}
		else if ($form_users->is_valid($post))
		{
			$this->db->insert('nf_teams_users', [
				'team_id' => $team_id,
				'user_id' => $post['user_id'],
				'role_id' => $post['role_id']
			]);
			
			refresh();
		}
		
		$this	->table
				->add_columns([
					[
						'content' => function($data){
							return NeoFrag()->user->link($data['user_id'], $data['username']);
						},
					],
					[
						'content' => function($data){
							return $data['title'];
						},
					],
					[
						'content' => [
							function($data) use ($team_id, $name){
								return $this->button_delete('admin/teams/players/delete/'.$team_id.'/'.$name.'/'.$data['user_id']);
							}
						],
						'size'    => TRUE
					]
				])
				->pagination(FALSE)
				->data($this->db->select('tu.user_id', 'u.username', 'r.title')->from('nf_teams_users tu')->join('nf_users u', 'u.user_id = tu.user_id AND u.deleted = "0"', 'INNER')->join('nf_teams_roles r', 'r.role_id = tu.role_id')->where('tu.team_id', $team_id)->order_by('r.title', 'u.username')->get())
				->no_data($this->lang('no_players_on_team'));
		
		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('edit_team'), 'fa-gamepad')
						->body($form_team->display())
						->size('col-md-12 col-lg-7')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('players'), 'fa-users')
						->body($this->table->display())
						->footer($this->view('users', [
							'users'   => $users,
							'roles'   => $roles,
							'form_id' => $form_users->token()
						]))
						->size('col-md-12 col-lg-5')
			)
		);
	}

	public function delete($team_id, $title)
	{
		$this	->title($this->lang('delete_team'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_team_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_team($team_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _roles_add()
	{
		$this	->subtitle($this->lang('add_role'))
				->form
				->add_rules('roles')
				->add_back('admin/teams')
				->add_submit($this->lang('add'));

		if ($this->form->is_valid($post))
		{
			$this->model('roles')->add_role($post['title']);

			notify($this->lang('add_role_success_message'));

			redirect_back('admin/teams');
		}
		
		return $this->panel()
					->heading($this->lang('add_role'), 'fa-sitemap')
					->body($this->form->display());
	}
	
	public function _roles_edit($role_id, $title)
	{
		$this	->subtitle($this->lang('role_', $title))
				->form
				->add_rules('roles', [
					'title' => $title
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/teams');
		
		if ($this->form->is_valid($post))
		{
			$this->model('roles')->edit_role($role_id, $post['title']);
		
			notify($this->lang('edit_role_success_message'));

			redirect_back('admin/teams');
		}
		
		return $this->panel()
					->heading($this->lang('edit_role'), 'fa-sitemap')
					->body($this->form->display());
	}
	
	public function _roles_delete($role_id, $title)
	{
		$this	->title($this->lang('delete_role'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_role_message', $title));
				
		if ($this->form->is_valid())
		{
			$this->model('roles')->delete_role($role_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _players_delete($team_id, $user_id, $username)
	{
		$this	->title($this->lang('delete_player'))
				->subtitle($username)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_player_message', $username));
				
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
