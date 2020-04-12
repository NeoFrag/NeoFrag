<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Games\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($maps)
	{
		$games = $this	->table()
						->add_columns([
							[
								'content' => function($data){
									$output = '<img src="'.NeoFrag()->model2('file', $data['icon_id'])->path().'" class="img-icon" alt="" /> '.$data['title'];
									return $data['parent_id'] ? '<span style="padding-left: 35px;">'.$output.'</span>' : $output;
								},
								'search'  => function($data){
									return $data['title'];
								}
							],
							[
								'content' => [
									function($data){
										return $this->is_authorized('modify_games') ? $this->button_update('admin/games/'.$data['game_id'].'/'.$data['name']) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_games') ? $this->button_delete('admin/games/delete/'.$data['game_id'].'/'.$data['name']) : NULL;
									}
								],
								'size'    => TRUE
							]
						])
						->data($this->model()->get_games())
						->no_data($this->lang('Il n\'y a pas encore de jeu'))
						->pagination(FALSE)
						->display();

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('Liste des jeux'), 'fas fa-gamepad')
						->body($games)
						->footer_if($this->is_authorized('add_games'), $this->button_create('admin/games/add', $this->lang('Ajouter un jeu')))
						->size('col-12 col-lg-4')
			),
			$this	->col($this->_panel_maps($maps))
					->size('col-12 col-lg-8')
		);
	}

	public function add()
	{
		$this	->title($this->lang('Jeux / Cartes'))
				->subtitle($this->lang('Ajouter un jeu'))
				->form()
				->add_rules('games', [
					'games' => $this->model()->get_games_list()
				])
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/games');

		if ($this->form()->is_valid($post))
		{
			$game_id = $this->model()->add_game($post['title'],
												$post['parent_id'],
												$post['image'],
												$post['icon']);

			notify($this->lang('Jeu ajouté avec succès'));
			redirect('admin/games/'.$game_id.'/'.url_title($post['title']));
		}

		return $this->panel()
					->heading($this->lang('Nouveau jeu'), 'fas fa-gamepad')
					->body($this->form()->display());
	}

	public function _edit($game_id, $parent_id, $image_id, $icon_id, $title, $game_name, $maps)
	{
		$this	->title($this->lang('Jeux / Cartes'))
				->subtitle($this->lang('Editer un jeu'))
				->form()
				->add_rules('games', [
					'games'     => $this->model()->get_games_list(FALSE, $game_id),
					'title'     => $title,
					'parent_id' => $parent_id,
					'image_id'  => $image_id,
					'icon_id'   => $icon_id
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/games');

		$modes = $this	->table()
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

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_game(	$game_id,
										$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);

			notify($this->lang('Jeu édité avec succès'));

			redirect_back('admin/games/'.$game_id.'/'.url_title($post['title']));
		}

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('Édition du jeu %s', $title), 'fas fa-gamepad')
						->body($this->form()->display())
						->size('col-7')
			),
			$this	->col(
						$this	->panel()
								->heading('Modes', 'fas fa-cog')
								->body($modes)
								->footer($this->button_create('admin/games/modes/add/'.$game_id.'/'.url_title($title),  'Ajouter un mode')),
						$this->_panel_maps($maps, $game_id, $title)
					)
					->size('col-5')
		);
	}

	public function delete($game_id, $title)
	{
		$this	->title($this->lang('Suppression d\'un jeu'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le jeu <b>%s</b> ?<br />Toutes les cartes et les équipes associées à ce jeu seront aussi supprimées.', $title));

		if ($this->form()->is_valid())
		{
			$this->model()->delete_game($game_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _maps_add($game_id = NULL, $game = NULL)
	{
		$this	->subtitle('Nouvelle carte')
				->form()
				->add_rules('maps', [
					'games'   => $this->model()->get_games_list(TRUE),
					'game_id' => $game_id
				])
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/games');

		if ($this->form()->is_valid($post))
		{
			$this->model('maps')->add_map(	$game_id = $game_id ?: $post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back('admin/games/'.$game_id.'/'.($game ?: $this->db->select('name')->from('nf_games')->where('game_id', $game_id)->row()));
		}

		return $this->panel()
					->heading('Nouvelle carte', 'far fa-map')
					->body($this->form()->display());
	}

	public function _maps_edit($map_id, $game_id, $image_id, $title, $game)
	{
		$this	->subtitle($title)
				->form()
				->add_rules('maps', [
					'games'    => $this->model()->get_games_list(TRUE),
					'game_id'  => $game_id,
					'title'    => $title,
					'image_id' => $image_id
				])
				->add_submit($this->lang('Éditer'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form()->is_valid($post))
		{
			$this->model('maps')->edit_map(	$map_id,
											$post['game_id'],
											$post['title'],
											$post['image']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Éditer la carte', 'far fa-map')
					->body($this->form()->display());
	}

	public function _maps_delete($map_id, $title)
	{
		$this	->title('Suppression d\'une carte')
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), 'Êtes-vous sûr(e) de vouloir supprimer la carte <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model('maps')->delete_map($map_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _modes_add($game_id, $game)
	{
		$this	->subtitle('Nouveau mode')
				->form()
				->add_rules('modes')
				->add_submit($this->lang('Ajouter'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form()->is_valid($post))
		{
			$this->model('modes')->add_mode($game_id, $post['title']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Nouveau mode', 'fas fa-cog')
					->body($this->form()->display());
	}

	public function _modes_edit($mode_id, $game_id, $title, $game)
	{
		$this	->title('Éditer le mode')
				->subtitle($title)
				->form()
				->add_rules('modes', [
					'title' => $title
				])
				->add_submit($this->lang('Éditer'))
				->add_back($back = 'admin/games/'.$game_id.'/'.$game);

		if ($this->form()->is_valid($post))
		{
			$this->model('modes')->edit_mode($mode_id, $post['title']);

			redirect_back($back);
		}

		return $this->panel()
					->heading('Éditer le mode', 'fas fa-cog')
					->body($this->form()->display());
	}

	public function _modes_delete($mode_id, $title)
	{
		$this	->title('Suppression d\'un mode')
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), 'Êtes-vous sûr(e) de vouloir supprimer le mode <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model('modes')->delete_mode($mode_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	private function _panel_maps($maps, $game_id = NULL, $title = NULL)
	{
		$maps = $this	->table()
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
									return ($data['icon_id'] ? '<img src="'.NeoFrag()->model2('file', $data['icon_id'])->path().'" class="img-icon" alt="" /> ' : '').'<a href="'.url('admin/games/'.$data['game_id'].'/'.$data['name']).'">'.$data['game_title'].'</a>';
								}
							],
							[
								'content' => [
									function($data){
										return $this->is_authorized('modify_games_maps') ? $this->button_update('admin/games/maps/edit/'.$data['map_id'].'/'.url_title($data['title'])) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_games_maps') ? $this->button_delete('admin/games/maps/delete/'.$data['map_id'].'/'.url_title($data['title'])) : NULL;
									}
								],
								'size'    => TRUE
							]
						]))
						->data($maps)
						->no_data('Aucune carte')
						->display();

		return $this->panel()
					->heading('Liste des cartes', 'far fa-map')
					->body($maps)
					->footer_if($this->is_authorized('add_games_maps'), $this->button_create('admin/games/maps/add'.($game_id ? '/'.$game_id.'/'.url_title($title) : ''),  'Ajouter une carte'));
	}
}
