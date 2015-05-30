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

class m_games_c_admin extends Controller_Module
{
	public function index($maps)
	{
		$games = $this	->load->library('table')
						->add_columns(array(
							array(
								'content' => '<?php if ($data[\'parent_id\']): ?><span style="padding-left: 35px;"><?php endif; ?><img src="{image {icon_id}}" alt="" /> {title}<?php if ($data[\'parent_id\']): ?></span><?php endif; ?>',
								'search'  => '{title}'
							),
							array(
								'content' => array(
									button_edit('{base_url}admin/games/{game_id}/{name}.html'),
									button_delete('{base_url}admin/games/delete/{game_id}/{name}.html')
								),
								'size'    => TRUE
							)
						))
						->data($this->model()->get_games())
						->no_data('Il n\'y a pas encore de jeu')
						->pagination(FALSE)
						->display();
		
		/*$maps = $this   ->table
						->add_columns(array(
							array(
								'title'   => 'Carte',
								'content' => '{title}',
								'search'  => '{title}'
							),
							array(
								'title'   => 'Jeu',
								'content' => '<?php if ($data[\'icon_id2\']): ?><img src="{image {icon_id2}}" alt="" /> <?php endif; ?><img src="{image {icon_id}}" alt="" /> {game}',
								'search'  => '{game}'
							),
							array(
								'content' => array(
									button_edit('{base_url}admin/games/{game_id}/{url_title(title)}.html'),
									button_delete('{base_url}admin/games/delete/{game_id}/{url_title(title)}.html')
								),
								'size'    => TRUE
							)
						))
						->data($maps)
						->no_data('Il n\'y a pas encore de carte')
						->display();*/
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Liste des jeux',
					'icon'    => 'fa-gamepad',
					'content' => $games,
					'footer'  => button_add('{base_url}admin/games/add.html', 'Ajouter un jeu'),
					'size'    => 'col-md-12 col-lg-4'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Liste des cartes',
					'icon'    => 'fa-picture-o',
					'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant. ',
					//'footer'  => button_add('{base_url}admin/games/add.html', 'Ajouter une carte'),
					'style'   => 'panel-info',
					'size'    => 'col-md-12 col-lg-8'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->title('Jeux / Cartes')
				->subtitle('Ajouter un jeu')
				->load->library('form')
				->add_rules('games', array(
					'games' => $this->model()->get_games_list(),
				))
				->add_submit('Ajouter')
				->add_back('admin/games.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_game(	$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);

			add_alert('Succes', 'Jeu ajouté avec succès');
			redirect_back('admin/games.html');
		}

		return new Panel(array(
			'title'   => 'Nouveau jeu',
			'icon'    => 'fa-gamepad',
			'content' => $this->form->display()
		));
	}
	
	public function _edit($game_id, $parent_id, $image_id, $icon_id, $title)
	{
		$this	->title('Jeux / Cartes')
				->subtitle('Editer un jeu')
				->load->library('form')
				->add_rules('games', array(
					'games'     => $this->model()->get_games_list($game_id),
					'title'     => $title,
					'parent_id' => $parent_id,
					'image_id'  => $image_id,
					'icon_id'   => $icon_id
				))
				->add_submit('Éditer')
				->add_back('admin/games.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_game(	$game_id,
										$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);
		
			add_alert('Succes', 'Jeu édité avec succès');

			redirect_back('admin/games.html');
		}
		
		$game = $this->form->display();
		
		return new Panel(array(
			'title'   => 'Édition du jeu '.$title,
			'icon'    => 'fa-gamepad',
			'content' => $game
		));
	}
	
	public function delete($game_id, $title)
	{
		$this	->title('Suppression d\'un jeu')
				->subtitle($title)
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le jeu <b>'.$title.'</b> ?<br />Toutes les cartes et les équipes associées à ce jeu seront aussi supprimées.');

		if ($this->form->is_valid())
		{
			$this->model()->delete_game($game_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1
./modules/games/controllers/admin.php
*/