<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Awards\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($awards)
	{
		$this->css('awards');

		$awards = $this	->table()
						->add_columns([
							[
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
							],
							[
								'title'   => 'Lieu',
								'content' => function($data){
									return $data['location'] ? icon('fas fa-map-marker-alt').$data['location'] : '';
								},
								'sort'    => function($data){
									return $data['location'];
								},
								'search'  => function($data){
									return $data['location'];
								}
							],
							[
								'title'   => 'Date',
								'content' => function($data){
									return timetostr(NeoFrag()->lang('%d/%m/%Y'), $data['date']);
								},
								'sort'    => function($data){
									return $data['date'];
								},
								'search'  => function($data){
									return $data['date'];
								}
							],
							[
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
							],
							[
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
							],
							[
								'title'   => '<span data-toggle="tooltip" title="Classement">'.icon('fas fa-trophy').'</span>',
								'size'    => TRUE,
								'content' => function($data){
									if ($data['ranking'] == 1)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'er / '.$data['participants'].' équipes">'.icon('fas fa-trophy trophy-gold').'</span>';
									}
									else if ($data['ranking'] == 2)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'ème / '.$data['participants'].' équipes">'.icon('fas fa-trophy trophy-silver').'</span>';
									}
									else if ($data['ranking'] == 3)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].'ème / '.$data['participants'].' équipes">'.icon('fas fa-trophy trophy-bronze').'</span>';
									}
									else
									{
										return $data['ranking'].'<small>ème</small>';
									}
								}
							],
							[
								'title'   => '<span data-toggle="tooltip" title="Plateforme">'.icon('fas fa-tv').'</span>',
								'size'    => TRUE,
								'content' => function($data){
									return $data['platform'];
								},
								'search'  => function($data){
									return $data['platform'];
								}
							],
							[
								'content' => [
									function($data){
										return $this->is_authorized('modify_awards') ? $this->button_update('admin/awards/'.$data['award_id'].'/'.url_title($data['name'])) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_awards') ? $this->button_delete('admin/awards/delete/'.$data['award_id'].'/'.url_title($data['name'])) : NULL;
									}
								],
								'size'    => TRUE
							]
						])
						->data($awards)
						->no_data('Aucun palmarès')
						->display();

		return $this->panel()
					->heading('Liste des palmarès', 'fas fa-trophy')
					->body($awards)
					->footer_if($this->is_authorized('add_awards'), $this->button_create('admin/awards/add', 'Ajouter un palmarès'));
	}

	public function add()
	{
		$this	->subtitle('Ajouter un palmarès')
				->form()
				->add_rules('awards', [
					'teams' => $this->model()->get_teams_list(),
					'games' => $this->model()->get_games_list()
				])
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/awards');

		if ($this->form()->is_valid($post))
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

			redirect_back('admin/awards');
		}

		return $this->panel()
					->heading('Nouveau palmarès', 'fas fa-trophy')
					->body($this->form()->display());
	}

	public function _edit($award_id, $team_id, $date, $location, $name, $platform, $game_id, $ranking, $participants, $description, $image_id, $team_name, $team_title, $game_name, $game_title)
	{
		$this	->subtitle('Équipe '.$team_title)
				->form()
				->add_rules('awards', [
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
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/awards');

		if ($this->form()->is_valid($post))
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

			redirect_back('admin/awards');
		}

		return $this->panel()
					->heading('Édition du palmarès', 'fas fa-trophy')
					->body($this->form()->display());
	}

	public function delete($award_id, $name)
	{
		$this	->title('Palmarès')
				->subtitle($name)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), 'Êtes-vous sûr de vouloir supprimer le palmarès <b>'.$name.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model()->delete_awards($award_id);

			return 'OK';
		}

		return $this->form()->display();
	}
}
