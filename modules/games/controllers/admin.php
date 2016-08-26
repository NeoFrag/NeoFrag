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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

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
										return button_edit('admin/games/'.$data['game_id'].'/'.$data['name'].'.html');
									},
									function($data){
										return button_delete('admin/games/delete/'.$data['game_id'].'/'.$data['name'].'.html');
									}
								],
								'size'    => TRUE
							]
						])
						->data($this->model()->get_games())
						->no_data($this('no_games'))
						->pagination(FALSE)
						->display();
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('game_list'),
					'icon'    => 'fa-gamepad',
					'content' => $games,
					'footer'  => button_add('admin/games/add.html', $this('add_game')),
					'size'    => 'col-md-12 col-lg-4'
				])
			),
			new Col(
				$this->_panel_maps($maps),
				'col-md-12 col-lg-8'
			)
		);
	}
	
	public function add()
	{
		$this	->title($this('games_maps'))
				->subtitle($this('add_game'))
				->form
				->add_rules('games', [
					'games' => $this->model()->get_games_list(),
				])
				->add_submit($this('add'))
				->add_back('admin/games.html');

		if ($this->form->is_valid($post))
		{
			$game_id = $this->model()->add_game($post['title'],
												$post['parent_id'],
												$post['image'],
												$post['icon']);

			notify($this('game_success_message'));
			redirect('admin/games/'.$game_id.'/'.url_title($post['title']).'.html');
		}

		return new Panel([
			'title'   => $this('new_game'),
			'icon'    => 'fa-gamepad',
			'content' => $this->form->display()
		]);
	}
	
	public function _edit($game_id, $parent_id, $image_id, $icon_id, $title, $game_name, $maps)
	{
		$this	->title($this('games_maps'))
				->subtitle($this('edit_game'))
				->form
				->add_rules('games', [
					'games'     => $this->model()->get_games_list(FALSE, $game_id),
					'title'     => $title,
					'parent_id' => $parent_id,
					'image_id'  => $image_id,
					'icon_id'   => $icon_id
				])
				->add_submit($this('edit'))
				->add_back('admin/games.html');

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
										return button_edit('admin/games/modes/edit/'.$data['mode_id'].'/'.url_title($data['title']).'.html');
									},
									function($data){
										return button_delete('admin/games/modes/delete/'.$data['mode_id'].'/'.url_title($data['title']).'.html');
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
		
			notify($this('edit_game_message'));

			redirect_back('admin/games/'.$game_id.'/'.url_title($post['title']).'.html');
		}
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('edit_game_title', $title),
					'icon'    => 'fa-gamepad',
					'content' => $this->form->display(),
					'size'    => 'col-md-7'
				])
			),
			new Col(
				new Panel([
					'title'   => 'Modes',
					'icon'    => 'fa-cog',
					'content' => $modes,
					'footer'  => button_add('admin/games/modes/add/'.$game_id.'/'.url_title($title).'.html',  'Ajouter un mode')
				]),
				$this->_panel_maps($maps, $game_id, $title),
				'col-md-5'
			)
		);
	}
	
	public function delete($game_id, $title)
	{
		$this	->title($this('delete_game'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_game_message', $title));

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
				->add_submit($this('add'))
				->add_back('admin/games.html');

		if ($this->form->is_valid($post))
		{
			$this->model('maps')->add_map(	$game_id = $game_id ?: $post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back('admin/games/'.$game_id.'/'.($game ?: $this->db->select('name')->from('nf_games')->where('game_id', $game_id)->row()).'.html');
		}

		return new Panel([
			'title'   => 'Nouvelle carte',
			'icon'    => 'fa-map-o',
			'content' => $this->form->display()
		]);
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
				->add_submit($this('edit'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game.'.html');

		if ($this->form->is_valid($post))
		{
			$this->model('maps')->edit_map(	$map_id,
											$post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back($back);
		}

		return new Panel([
			'title'   => 'Éditer la carte',
			'icon'    => 'fa-map-o',
			'content' => $this->form->display()
		]);
	}
	
	public function _maps_delete($map_id, $title)
	{
		$this	->title('Suppression d\'une carte')
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer la carte <b>'.$title.'</b> ?');

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
				->add_submit($this('add'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game.'.html');

		if ($this->form->is_valid($post))
		{
			$this->model('modes')->add_mode($game_id, $post['title']);

			redirect_back($back);
		}

		return new Panel([
			'title'   => 'Nouveau mode',
			'icon'    => 'fa-cog',
			'content' => $this->form->display()
		]);
	}
	
	public function _modes_edit($mode_id, $game_id, $title, $game)
	{
		$this	->title('Éditer le mode')
				->subtitle($title)
				->form
				->add_rules('modes', [
					'title' => $title
				])
				->add_submit($this('edit'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game.'.html');

		if ($this->form->is_valid($post))
		{
			$this->model('modes')->edit_mode($mode_id, $post['title']);

			redirect_back($back);
		}

		return new Panel([
			'title'   => 'Éditer le mode',
			'icon'    => 'fa-cog',
			'content' => $this->form->display()
		]);
	}
	
	public function _modes_delete($mode_id, $title)
	{
		$this	->title('Suppression d\'un mode')
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le mode <b>'.$title.'</b> ?');

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
									return ($data['icon_id'] ? '<img src="'.path($data['icon_id']).'" alt="" /> ' : '').'<a href="'.url('admin/games/'.$data['game_id'].'/'.$data['name'].'.html').'">'.$data['game_title'].'</a>';
								}
							],
							[
								'content' => [
									function($data){
										return button_edit('admin/games/maps/edit/'.$data['map_id'].'/'.url_title($data['title']).'.html');
									},
									function($data){
										return button_delete('admin/games/maps/delete/'.$data['map_id'].'/'.url_title($data['title']).'.html');
									}
								],
								'size'    => TRUE
							]
						]))
						->data($maps)
						->no_data('Aucune carte')
						->display();

		return new Panel([
			'title'   => 'Cartes',
			'icon'    => 'fa-map-o',
			'content' => $maps,
			'footer'  => button_add('admin/games/maps/add'.($game_id ? '/'.$game_id.'/'.url_title($title) : '').'.html',  'Ajouter une carte')
		]);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/games/controllers/admin.php
*/