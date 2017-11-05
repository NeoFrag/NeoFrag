<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_games_c_admin extends Controller_Module
{
	public function index($maps)
	{
		$games = $this	->table
						->add_columns([
							[
								'content' => function($data){
									$output = '<img src="'.path($data['icon_id']).'" alt="" /> '.$data['title'];
									return $data['parent_id'] ? '<span style="padding-left: 35px;">'.$output.'</span>' : $output;
								},
								'search'  => function($data){
									return $data['title'];
								}
							],
							[
								'content' => [
									function($data){
										return $this->button_update('admin/games/'.$data['game_id'].'/'.$data['name']);
									},
									function($data){
										return $this->button_delete('admin/games/delete/'.$data['game_id'].'/'.$data['name']);
									}
								],
								'size'    => TRUE
							]
						])
						->data($this->model()->get_games())
						->no_data($this->lang('no_games'))
						->pagination(FALSE)
						->display();
		
		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('game_list'), 'fa-gamepad')
						->body($games)
						->footer($this->button_create('admin/games/add', $this->lang('add_game')))
						->size('col-md-12 col-lg-4')
			),
			$this	->col($this->_panel_maps($maps))
					->size('col-md-12 col-lg-8')
		);
	}
	
	public function add()
	{
		$this	->title($this->lang('games_maps'))
				->subtitle($this->lang('add_game'))
				->form
				->add_rules('games', [
					'games' => $this->model()->get_games_list(),
				])
				->add_submit($this->lang('add'))
				->add_back('admin/games');

		if ($this->form->is_valid($post))
		{
			$game_id = $this->model()->add_game($post['title'],
												$post['parent_id'],
												$post['image'],
												$post['icon']);

			notify($this->lang('game_success_message'));
			redirect('admin/games/'.$game_id.'/'.url_title($post['title']));
		}

		return $this->panel()
					->heading($this->lang('new_game'), 'fa-gamepad')
					->body($this->form->display());
	}
	
	public function _edit($game_id, $parent_id, $image_id, $icon_id, $title, $game_name, $maps)
	{
		$this	->title($this->lang('games_maps'))
				->subtitle($this->lang('edit_game'))
				->form
				->add_rules('games', [
					'games'     => $this->model()->get_games_list(FALSE, $game_id),
					'title'     => $title,
					'parent_id' => $parent_id,
					'image_id'  => $image_id,
					'icon_id'   => $icon_id
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/games');

		$modes = $this	->table
						->add_columns([
							[
								'title'   => 'Titre',
								'content' => function($data){
									return $data['title'];
								}
							],
							[
								'content' => [
									function($data){
										return $this->button_update('admin/games/modes/edit/'.$data['mode_id'].'/'.url_title($data['title']));
									},
									function($data){
										return $this->button_delete('admin/games/modes/delete/'.$data['mode_id'].'/'.url_title($data['title']));
									}
								],
								'size'    => TRUE
							]
						])
						->data($this->model('modes')->get_modes($game_id))
						->no_data('Aucun mode')
						->pagination(FALSE)
						->display();
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_game(	$game_id,
										$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);
		
			notify($this->lang('edit_game_message'));

			redirect_back('admin/games/'.$game_id.'/'.url_title($post['title']));
		}
		
		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('edit_game_title', $title), 'fa-gamepad')
						->body($this->form->display())
						->size('col-md-7')
			),
			$this	->col(
						$this	->panel()
								->heading('Modes', 'fa-cog')
								->body($modes)
								->footer($this->button_create('admin/games/modes/add/'.$game_id.'/'.url_title($title),  'Ajouter un mode')),
						$this->_panel_maps($maps, $game_id, $title)
					)
					->size('col-md-5')
		);
	}
	
	public function delete($game_id, $title)
	{
		$this	->title($this->lang('delete_game'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_game_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_game($game_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _maps_add($game_id = NULL, $game = NULL)
	{
		$this	->subtitle('Nouvelle carte')
				->form
				->add_rules('maps', [
					'games'   => $this->model()->get_games_list(TRUE),
					'game_id' => $game_id
				])
				->add_submit($this->lang('add'))
				->add_back('admin/games');

		if ($this->form->is_valid($post))
		{
			$this->model('maps')->add_map(	$game_id = $game_id ?: $post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back('admin/games/'.$game_id.'/'.($game ?: $this->db->select('name')->from('nf_games')->where('game_id', $game_id)->row()));
		}

		return $this->panel()
					->heading('Nouvelle carte', 'fa-map-o')
					->body($this->form->display());
	}
	
	public function _maps_edit($map_id, $game_id, $image_id, $title, $game)
	{
		$this	->title('Éditer la carte')
				->subtitle($title)
				->form
				->add_rules('maps', [
					'games'    => $this->model()->get_games_list(TRUE),
					'game_id'  => $game_id,
					'title'    => $title,
					'image_id' => $image_id
				])
				->add_submit($this->lang('edit'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form->is_valid($post))
		{
			$this->model('maps')->edit_map(	$map_id,
											$post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Éditer la carte', 'fa-map-o')
					->body($this->form->display());
	}
	
	public function _maps_delete($map_id, $title)
	{
		$this	->title('Suppression d\'une carte')
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer la carte <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model('maps')->delete_map($map_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _modes_add($game_id, $game)
	{
		$this	->subtitle('Nouveau mode')
				->form
				->add_rules('modes')
				->add_submit($this->lang('add'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form->is_valid($post))
		{
			$this->model('modes')->add_mode($game_id, $post['title']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Nouveau mode', 'fa-cog')
					->body($this->form->display());
	}
	
	public function _modes_edit($mode_id, $game_id, $title, $game)
	{
		$this	->title('Éditer le mode')
				->subtitle($title)
				->form
				->add_rules('modes', [
					'title' => $title
				])
				->add_submit($this->lang('edit'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form->is_valid($post))
		{
			$this->model('modes')->edit_mode($mode_id, $post['title']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Éditer le mode', 'fa-cog')
					->body($this->form->display());
	}
	
	public function _modes_delete($mode_id, $title)
	{
		$this	->title('Suppression d\'un mode')
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le mode <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model('modes')->delete_mode($mode_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	private function _panel_maps($maps, $game_id = NULL, $title = NULL)
	{
		$maps = $this	->table
						->add_columns(array_filter([
							[
								'title'   => 'Titre',
								'content' => function($data){
									return $data['title'];
								}
							],
							$game_id ? NULL : [
								'title'   => 'Jeu',
								'content' => function($data){
									return ($data['icon_id'] ? '<img src="'.path($data['icon_id']).'" alt="" /> ' : '').'<a href="'.url('admin/games/'.$data['game_id'].'/'.$data['name']).'">'.$data['game_title'].'</a>';
								}
							],
							[
								'content' => [
									function($data){
										return $this->button_update('admin/games/maps/edit/'.$data['map_id'].'/'.url_title($data['title']));
									},
									function($data){
										return $this->button_delete('admin/games/maps/delete/'.$data['map_id'].'/'.url_title($data['title']));
									}
								],
								'size'    => TRUE
							]
						]))
						->data($maps)
						->no_data('Aucune carte')
						->display();

		return $this->panel()
					->heading('Cartes', 'fa-map-o')
					->body($maps)
					->footer($this->button_create('admin/games/maps/add'.($game_id ? '/'.$game_id.'/'.url_title($title) : ''),  'Ajouter une carte'));
	}
}
