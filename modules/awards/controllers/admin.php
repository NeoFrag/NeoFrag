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
								'title'   => $this->lang('Titre'),
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
								'title'   => $this->lang('Lieu'),
								'content' => function($data){
									return $data['location'] ? icon('fa-map-marker').$data['location'] : '';
								},
								'sort'    => function($data){
									return $data['location'];
								},
								'search'  => function($data){
									return $data['location'];
								}
							],
							[
								'title'   => $this->lang('Date'),
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
								'title'   => $this->lang('Équipe'),
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
								'title'   => $this->lang('Jeu'),
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
								'title'   => '<span data-toggle="tooltip" title="'.$this->lang('Classement').'">'.icon('fa-trophy').'</span>',
								'size'    => TRUE,
								'content' => function($data){
									if ($data['ranking'] == 1)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].$this->lang('er')' / '.$data['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-gold').'</span>';
									}
									else if ($data['ranking'] == 2)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].$this->lang('ème')' / '.$data['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-silver').'</span>';
									}
									else if ($data['ranking'] == 3)
									{
										return '<span data-toggle="tooltip" title="'.$data['ranking'].$this->lang('ème')' / '.$data['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-bronze').'</span>';
									}
									else
									{
										return $data['ranking'].'<small>'.$this->lang('ème').'</small>';
									}
								}
							],
							[
								'title'   => '<span data-toggle="tooltip" title="'.$this->lang('Plateforme').'">'.icon('fa-tv').'</span>',
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
										return $this->button_update('admin/awards/'.$data['award_id'].'/'.url_title($data['name']));
									},
									function($data){
										return $this->button_delete('admin/awards/delete/'.$data['award_id'].'/'.url_title($data['name']));
									}
								],
								'size'    => TRUE
							]
						])
						->data($awards)
						->no_data($this->lang('Aucun palmarès'))
						->display();

		return $this->panel()
					->heading($this->lang('Liste des palmarès'), 'fa-trophy')
					->body($awards)
					->footer($this->button_create('admin/awards/add', $this->lang('Ajouter un palmarès')));
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter un palmarès'))
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

			notify($this->lang('Palmarès ajouté avec succès'));

			redirect_back('admin/awards');
		}

		return $this->panel()
					->heading($this->lang('Nouveau palmarès'), 'fa-trophy')
					->body($this->form()->display());
	}

	public function _edit($award_id, $team_id, $date, $location, $name, $platform, $game_id, $ranking, $participants, $description, $image_id, $team_name, $team_title, $game_name, $game_title)
	{
		$this	->subtitle($this->lang('Équipe').' '.$team_title)
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

			notify($this->lang('Palmarès édité avec succès'));

			redirect_back('admin/awards');
		}

		return $this->panel()
					->heading($this->lang('Édition du palmarès'), 'fa-trophy')
					->body($this->form()->display());
	}

	public function delete($award_id, $name)
	{
		$this	->title($this->lang('Palmarès'))
				->subtitle($name)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr de vouloir supprimer le palmarès')' <b>'.$name.'</b>?');

		if ($this->form()->is_valid())
		{
			$this->model()->delete_awards($award_id);

			return $this->lang('OK');
		}

		return $this->form()->display();
	}
}
