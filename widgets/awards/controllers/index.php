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

class w_awards_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		if ($awards = $this->model()->get_awards())
		{
			$this->css('awards');

			return new Panel([
				'title'        => 'Nos derniers palmarès',
				'content'      => $this->load->view('index', [
					'awards' => array_slice($awards, 0, 5)
				]),
				'body'         => FALSE,
				'footer'       => '<a href="'.url('awards.html').'">'.icon('fa-arrow-circle-o-right').' Tous nos palmarès</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => 'Palmarès',
				'content' => 'Aucun palmarès pour le moment...'
			]);
		}
	}
	
	public function best_team($settings = [])
	{
		if ($best_team = $this->model()->get_best_team_awards())
		{
			return new Panel([
				'title'   => 'Palmarès',
				'content' => $this->load->view('best_team', [
					'team_id'    => $best_team[0]['team_id'],
					'name'       => $best_team[0]['name'],
					'team_title' => $best_team[0]['team_title'],
					'nb_awards'  => $best_team[0]['nb_awards']
				]),
				'footer'       => '<a href="'.url('awards.html').'">'.icon('fa-arrow-circle-o-right').' Tous nos palmarès</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => 'Palmarès',
				'content' => 'Aucun palmarès pour le moment...'
			]);
		}
	}
	
	public function best_game($settings = [])
	{
		if ($best_game = $this->model()->get_best_game_awards())
		{
			return new Panel([
				'title'   => 'Palmarès',
				'content' => $this->load->view('best_game', [
					'game_id'    => $best_game[0]['game_id'],
					'name'       => $best_game[0]['name'],
					'game_title' => $best_game[0]['game_title'],
					'nb_awards'  => $best_game[0]['nb_awards']
				]),
				'footer'       => '<a href="'.url('awards.html').'">'.icon('fa-arrow-circle-o-right').' Tous nos palmarès</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => 'Palmarès',
				'content' => 'Aucun palmarès pour le moment...'
			]);
		}
	}
}

/*
NeoFrag Alpha 0.1.4
./widgets/awards/controllers/index.php
*/