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
		$games = $this	->load->library('table')
						->add_columns(array(
							array(
								'content' => function($data){
									$output = '<img src="'.path($data['icon_id']).'" alt="" /> '.$data['title'];
									return $data['parent_id'] ? '<span style="padding-left: 35px;">'.$output.'</span>' : $output;
								},
								'search'  => function($data){
									return $data['title'];
								}
							),
							array(
								'content' => array(
									function($data){
										return button_edit('admin/games/'.$data['game_id'].'/'.$data['name'].'.html');
									},
									function($data){
										return button_delete('admin/games/delete/'.$data['game_id'].'/'.$data['name'].'.html');
									}
								),
								'size'    => TRUE
							)
						))
						->data($this->model()->get_games())
						->no_data($this('no_games'))
						->pagination(FALSE)
						->display();
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => $this('game_list'),
					'icon'    => 'fa-gamepad',
					'content' => $games,
					'footer'  => button_add('admin/games/add.html', $this('add_game')),
					'size'    => 'col-md-12 col-lg-4'
				))
			),
			new Col(
				new Panel(array(
					'title'   => $this('maps_list_title'),
					'icon'    => 'fa-picture-o',
					'content' => $this('unavailable_feature'),
					//'footer'  => button_add('admin/games/add.html', 'Ajouter une carte'),
					'style'   => 'panel-info',
					'size'    => 'col-md-12 col-lg-8'
				))
			)
		);
	}
	
	public function add()
	{
		$this	->title($this('games_maps'))
				->subtitle($this('add_game'))
				->load->library('form')
				->add_rules('games', array(
					'games' => $this->model()->get_games_list(),
				))
				->add_submit($this('add'))
				->add_back('admin/games.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_game(	$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);

			//add_alert('success', $this('game_success_message'));
			redirect_back('admin/games.html');
		}

		return new Panel(array(
			'title'   => $this('new_game'),
			'icon'    => 'fa-gamepad',
			'content' => $this->form->display()
		));
	}
	
	public function _edit($game_id, $parent_id, $image_id, $icon_id, $title)
	{
		$this	->title($this('games_maps'))
				->subtitle($this('edit_game'))
				->load->library('form')
				->add_rules('games', array(
					'games'     => $this->model()->get_games_list($game_id),
					'title'     => $title,
					'parent_id' => $parent_id,
					'image_id'  => $image_id,
					'icon_id'   => $icon_id
				))
				->add_submit($this('edit'))
				->add_back('admin/games.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_game(	$game_id,
										$post['title'],
										$post['parent_id'],
										$post['image'],
										$post['icon']);
		
			//add_alert('success', $this('edit_game_message'));

			redirect_back('admin/games.html');
		}
		
		$game = $this->form->display();
		
		return new Panel(array(
			'title'   => $this('edit_game_title', $title),
			'icon'    => 'fa-gamepad',
			'content' => $game
		));
	}
	
	public function delete($game_id, $title)
	{
		$this	->title($this('delete_game'))
				->subtitle($title)
				->load->library('form')
				->confirm_deletion($this('delete_confirmation'), $this('delete_game_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_game($game_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.2
./modules/games/controllers/admin.php
*/