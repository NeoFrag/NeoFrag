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

class m_awards_c_admin extends Controller_Module
{
	public function index($awards)
	{
		$this->load	->library('table')
					->css('awards');

		$awards = $this	->table
						->add_columns(array(
							array(
								'title'   => 'Titre',
								'content' => function($data){
									return $data['name'];
								},
								'sort'    => function($data){
									return $data['name'];
								},
								'search'  => function($data){
									return $data['name'];
								}
							),
							array(
								'title'   => 'Lieu',
								'content' => function($data){
									return $data['location'] ? icon('fa-map-marker').$data['location'] : '';
								},
								'sort'    => function($data){
									return $data['location'];
								},
								'search'  => function($data){
									return $data['location'];
								}
							),
							array(
								'title'   => 'Date',
								'content' => function($data){
									return timetostr(NeoFrag::loader()->lang('date_short'), $data['date']);
								},
								'sort'    => function($data){
									return $data['date'];
								},
								'search'  => function($data){
									return $data['date'];
								}
							),
							array(
								'title'   => 'Équipe',
								'content' => function($data){
									return $data['team_title'];
								},
								'sort'    => function($data){
									return $data['team_title'];
								},
								'search'  => function($data){
									return $data['team_title'];
								}
							),
							array(
								'title'   => 'Jeu',
								'content' => function($data){
									return $data['game_title'];
								},
								'sort'    => function($data){
									return $data['game_title'];
								},
								'search'  => function($data){
									return $data['game_title'];
								}
							),
							array(
								'title'   => '<span data-toggle="tooltip" title="Classement">'.icon('fa-trophy').'</span>',
								'size'    => TRUE,
								'content' => function($data){
									if ($data['ranking'] == 1)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'er / '.$data['participants'].' équipes">'.icon('fa-trophy trophy-gold').'</span>';
									}
									else if ($data['ranking'] == 2)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'ème / '.$data['participants'].' équipes">'.icon('fa-trophy trophy-silver').'</span>';
									}
									else if ($data['ranking'] == 3)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'ème / '.$data['participants'].' équipes">'.icon('fa-trophy trophy-bronze').'</span>';
									}
									else
									{
										return $data['ranking'].'<small>ème</small>';
									}
								}
							),
							array(
								'title'   => '<span data-toggle="tooltip" title="Plateforme">'.icon('fa-tv').'</span>',
								'size'    => TRUE,
								'content' => function($data){
									return $data['platform'];
								},
								'search'  => function($data){
									return $data['platform'];
								}
							),
							array(
								'content' => array(
									function($data){
										return button_edit('admin/awards/'.$data['award_id'].'/'.url_title($data['name']).'.html');
									},
									function($data){
										return button_delete('admin/awards/delete/'.$data['award_id'].'/'.url_title($data['name']).'.html');
									}
								),
								'size'    => TRUE
							)
						))
						->data($awards)
						->no_data('Aucun palmarès')
						->display();

		return new Panel(array(
			'title'   => 'Liste des palmarès',
			'icon'    => 'fa-trophy',
			'content' => $awards,
			'footer'  => button_add('admin/awards/add.html', 'Ajouter un palmarès')
		));
	}

	public function add()
	{
		$this	->subtitle('Ajouter un palmarès')
				->load->library('form')
				->add_rules('awards', array(
					'teams' => $this->model()->get_teams_list(),
					'games' => $this->model()->get_games_list(),
				))
				->add_submit($this('add'))
				->add_back('admin/awards.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_awards(	$post['date'],
										$post['team'],
										$post['game'],
										$post['platform'],
										$post['location'],
										$post['name'],
										$post['ranking'],
										$post['participants'],
										$post['description'],
										$post['image']);

			notify('Palmarès ajouté avec succès');

			redirect_back('admin/awards.html');
		}

		return new Panel(array(
			'title'   => 'Nouveau palmarès',
			'icon'    => 'fa-trophy',
			'content' => $this->form->display()
		));
	}

	public function _edit($award_id, $team_id, $date, $location, $name, $platform, $game_id, $ranking, $participants, $description, $image_id, $team_name, $team_title, $game_name, $game_title)
	{
		$this	->subtitle('Équipe '.$team_title)
				->load->library('form')
				->add_rules('awards', array(
					'award_id'     => $award_id,
					'date'         => $date,
					'team_id'      => $team_id,
					'teams'        => $this->model()->get_teams_list(),
					'game_id'      => $game_id,
					'games'        => $this->model()->get_games_list(),
					'platform'     => $platform,
					'location'     => $location,
					'name'         => $name,
					'ranking'      => $ranking,
					'participants' => $participants,
					'description'  => $description,
					'image'        => $image_id
				))
				->add_submit($this('edit'))
				->add_back('admin/awards.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_awards($award_id,
										$post['date'],
										$post['team'],
										$post['game'],
										$post['platform'],
										$post['location'],
										$post['name'],
										$post['ranking'],
										$post['participants'],
										$post['description'],
										$post['image']);

			notify('Palmarès édité avec succès');

			redirect_back('admin/awards.html');
		}

		return new Panel(array(
			'title'   => 'Édition du palmarès',
			'icon'    => 'fa-trophy',
			'content' => $this->form->display()
		));
	}

	public function delete($award_id, $name)
	{
		$this	->title('Palmarès')
				->subtitle($name)
				->load->library('form')
				->confirm_deletion($this('delete_confirmation'), 'Êtes-vous sûr de vouloir supprimer le palmarès <b>'.$name.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->model()->delete_awards($award_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/awards/controllers/admin.php
*/