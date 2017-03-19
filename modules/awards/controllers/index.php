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

class m_awards_c_index extends Controller_Module
{
	public function index()
	{
		$this->css('awards');

		$awards_stats = $this	->panel()
								->heading('Palmarès', 'fa-trophy')
								->body($this->load->view('resume', [
									'teams'        => $this->model()->get_teams_ranking(3),
									'total_gold'   => $this->model()->count_awards(1, NULL, NULL),
									'total_silver' => $this->model()->count_awards(2, NULL, NULL),
									'total_bronze' => $this->model()->count_awards(3, NULL, NULL)
								]))
								->footer('<a href="'.url('awards/statistics.html').'">'.icon('fa-line-chart').' Voir toutes nos statistiques</a>');

		$panels = [];

		foreach ($this->model()->get_years() as $year)
		{
			$panels[] = $this	->panel()
								->heading('Année '.$year, 'fa-calendar-o')
								->body($this->load->view('index', [
									'stats-team' => FALSE,
									'stats-game' => FALSE,
									'awards'     => $this->model()->get_awards('date', $year)
								]), FALSE);
		}

		array_unshift($panels, $awards_stats);

		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading('Palmarès', 'fa-trophy')
								->body('<div class="text-center">'.$this('no_award_yet').'</div>')
								->color('info');
		}

		return $panels;
	}

	public function statistics()
	{
		$this	->css('awards')
				->js('jquery.knob')
				->js_load('$(\'.knob\').knob();');

		return [
			$this	->panel()
					->heading('Les trophées de nos équipes', 'fa-trophy')
					->body($this->load->view('statistics', [
						'total_silver'     => $this->model()->count_awards(2, NULL, NULL),
						'total_gold'       => $this->model()->count_awards(1, NULL, NULL),
						'total_bronze'     => $this->model()->count_awards(3, NULL, NULL),
						'best_team_awards' => $this->model()->get_best_team_awards(),
						'best_game_awards' => $this->model()->get_best_game_awards(),
						'best_team'        => $this->model()->get_teams_ranking(1),
						'teams'            => $this->model()->get_teams_ranking(),
					])),
			$this->panel_back()
		];
	}

	public function _award($award_id, $team_id, $date, $location, $name, $platform, $game_id, $ranking, $participants, $description, $image_id, $team_name, $team_title, $game_name, $game_title)
	{
		$this->css('awards');

		return [
			$this	->panel()
					->heading($name, 'fa-trophy')
					->body($this->load->view('award', [
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
					]), FALSE),
			$this->comments->display('awards', $award_id),
			$this->panel_back()
		];
	}

	public function _filter($filter, $data_id, $name)
	{
		$this->css('awards');

		if ($filter == 'team' && $team = $this->model()->check_team($data_id, $name))
		{
			return [
				$this	->panel()
						->heading('Palmarès de l\'équipe '.$team['title'], 'fa-trophy')
						->body($this->load->view('index', [
							'stats-team' => TRUE,
							'stats-game' => FALSE,
							'image_id'   => $team['image_id'],
							'awards'     => $this->model()->get_awards('team', $data_id),
							'total_silver' => $this->model()->count_awards(2, 'team', $data_id),
							'total_gold'   => $this->model()->count_awards(1, 'team', $data_id),
							'total_bronze' => $this->model()->count_awards(3, 'team', $data_id)
						]), FALSE),
				$this->panel_back()
			];
		}
		else if ($filter == 'game' && $game = $this->model()->check_game($data_id, $name))
		{
			return [
				$this	->panel()
						->heading('Palmarès sur le jeu '.$game['title'], 'fa-trophy')
						->body($this->load->view('index', [
							'stats-team' => FALSE,
							'stats-game' => TRUE,
							'image_id'   => $game['image_id'],
							'awards'     => $this->model()->get_awards('game', $data_id),
							'total_silver' => $this->model()->count_awards(2, 'game', $data_id),
							'total_gold'   => $this->model()->count_awards(1, 'game', $data_id),
							'total_bronze' => $this->model()->count_awards(3, 'game', $data_id)
						]), FALSE),
				$this->panel_back()
			];
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/awards/controllers/index.php
*/