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

class w_teams_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		$this->css('teams');
		
		$teams = array_filter($this->model()->get_teams(), function($a){
			return $a['image_id'];
		});
		
		if (!empty($teams))
		{
			return new Panel([
				'title'        => $this('ours_teams'),
				'content'      => $this->load->view('index', [
					'teams'    => $teams
				]),
				'body'         => FALSE,
				'footer'       => '<a href="'.url('teams.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('see_all_teams').'</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('ours_teams'),
				'content' => $this('no_team')
			]);
		}
	}
}

/*
NeoFrag Alpha 0.1.3
./widgets/teams/controllers/index.php
*/