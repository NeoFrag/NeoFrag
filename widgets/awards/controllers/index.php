<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Awards\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		if ($awards = $this->module('awards')->model()->get_awards())
		{
			$this->module('awards')->css('awards');

			return $this->panel()
						->heading('Nos derniers palmarès')
						->body($this->view('index', [
							'awards' => array_slice($awards, 0, 5)
						]), FALSE)
						->footer('<a href="'.url('awards').'">'.icon('far fa-arrow-alt-circle-right').' Tous nos palmarès</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading('Palmarès')
						->body('Aucun palmarès pour le moment...');
		}
	}

	public function best_team($settings = [])
	{
		if ($best_team = $this->module('awards')->model()->get_best_team_awards())
		{
			return $this->panel()
						->heading('Palmarès')
						->body($this->view('best_team', [
							'team_id'    => $best_team[0]['team_id'],
							'name'       => $best_team[0]['name'],
							'team_title' => $best_team[0]['team_title'],
							'nb_awards'  => $best_team[0]['nb_awards']
						]))
						->footer('<a href="'.url('awards').'">'.icon('far fa-arrow-alt-circle-right').' Tous nos palmarès</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading('Palmarès')
						->body('Aucun palmarès pour le moment...');
		}
	}

	public function best_game($settings = [])
	{
		if ($best_game = $this->module('awards')->model()->get_best_game_awards())
		{
			return $this->panel()
						->heading('Palmarès')
						->body($this->view('best_game', [
							'game_id'    => $best_game[0]['game_id'],
							'name'       => $best_game[0]['name'],
							'game_title' => $best_game[0]['game_title'],
							'nb_awards'  => $best_game[0]['nb_awards']
						]))
						->footer('<a href="'.url('awards').'">'.icon('far fa-arrow-alt-circle-right').' Tous nos palmarès</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading('Palmarès')
						->body('Aucun palmarès pour le moment...');
		}
	}
}
