<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Awards\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		$this->css('awards');

		$panels = $this->array;

		$panels->append($this	->panel()
								->heading('Palmarès', 'fas fa-trophy')
								->body($this->view('resume', [
									'teams'        => $this->model()->get_teams_ranking(3),
									'total_gold'   => $this->model()->count_awards(1, NULL, NULL),
									'total_silver' => $this->model()->count_awards(2, NULL, NULL),
									'total_bronze' => $this->model()->count_awards(3, NULL, NULL)
								]))
								->footer('<a href="'.url('awards/statistics').'">'.icon('fas fa-chart-line').' Voir toutes nos statistiques</a>'));

		foreach ($this->model()->get_years() as $year)
		{
			$panels->append($this	->panel()
									->heading('Année '.$year, 'far fa-calendar')
									->body($this->view('index', [
										'stats-team' => FALSE,
										'stats-game' => FALSE,
										'awards'     => $this->model()->get_awards('date', $year)
									]), FALSE));
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading('Palmarès', 'fas fa-trophy')
									->body('<div class="text-center">'.$this->lang('no_award_yet').'</div>')
									->color('info'));
		}

		return $panels;
	}

	public function statistics()
	{
		$this	->css('awards')
				->js('jquery.knob')
				->js_load('$(\'.knob\').knob();');

		return $this->array
					->append($this	->panel()
									->heading('Les trophées de nos équipes', 'fas fa-trophy')
									->body($this->view('statistics', [
										'total_silver'     => $this->model()->count_awards(2, NULL, NULL),
										'total_gold'       => $this->model()->count_awards(1, NULL, NULL),
										'total_bronze'     => $this->model()->count_awards(3, NULL, NULL),
										'best_team_awards' => $this->model()->get_best_team_awards(),
										'best_game_awards' => $this->model()->get_best_game_awards(),
										'best_team'        => $this->model()->get_teams_ranking(1),
										'teams'            => $this->model()->get_teams_ranking()
									]), FALSE)
					)
					->append($this->panel_back());
	}

	public function _award($award_id, $team_id, $date, $location, $name, $platform, $game_id, $ranking, $participants, $description, $image_id, $team_name, $team_title, $game_name, $game_title)
	{
		return $this->css('awards')
					->array
					->append($this	->panel()
									->heading($name, 'fas fa-trophy')
									->body($this->view('award', [
										'game_id'      => $game_id,
										'team_id'      => $team_id,
										'team_name'    => $team_name,
										'team_title'   => $team_title,
										'date'         => $date,
										'name'         => $name,
										'location'     => $location,
										'platform'     => $platform,
										'ranking'      => $ranking,
										'participants' => $participants,
										'description'  => bbcode($description),
										'image_id'     => $image_id,
										'game_name'    => $game_name,
										'game_title'   => $game_title
									]), FALSE)
					)
					->append_if(($comments = $this->module('comments')) && $comments->is_enabled(), function() use (&$comments, $award_id){
						return $comments('awards', $award_id);
					})
					->append($this->panel_back());
	}

	public function _filter($filter, $data_id, $name)
	{
		$this->css('awards');

		if ($filter == 'team' && $team = $this->model()->check_team($data_id, $name))
		{
			return $this->array
						->append($this	->panel()
										->heading('Palmarès de l\'équipe '.$team['title'], 'fas fa-trophy')
										->body($this->view('index', [
											'stats-team' => TRUE,
											'stats-game' => FALSE,
											'image_id'   => $team['image_id'],
											'awards'     => $this->model()->get_awards('team', $data_id),
											'total_silver' => $this->model()->count_awards(2, 'team', $data_id),
											'total_gold'   => $this->model()->count_awards(1, 'team', $data_id),
											'total_bronze' => $this->model()->count_awards(3, 'team', $data_id)
										]), FALSE)
						)
						->append($this->panel_back());
		}
		else if ($filter == 'game' && $game = $this->model()->check_game($data_id, $name))
		{
			return $this->array
						->append($this	->panel()
										->heading('Palmarès sur le jeu '.$game['title'], 'fas fa-trophy')
										->body($this->view('index', [
											'stats-team' => FALSE,
											'stats-game' => TRUE,
											'image_id'   => $game['image_id'],
											'awards'     => $this->model()->get_awards('game', $data_id),
											'total_silver' => $this->model()->count_awards(2, 'game', $data_id),
											'total_gold'   => $this->model()->count_awards(1, 'game', $data_id),
											'total_bronze' => $this->model()->count_awards(3, 'game', $data_id)
										]), FALSE)
						)
						->append($this->panel_back());
		}
		else
		{
			$this->error();
		}
	}
}
