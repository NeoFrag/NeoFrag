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
			return $this->panel()
						->heading($this->lang('ours_teams'))
						->body($this->view('index', [
							'teams'    => $teams
						]), FALSE)
						->footer('<a href="'.url('teams').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('see_all_teams').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('ours_teams'))
						->body($this->lang('no_team'));
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./widgets/teams/controllers/index.php
*/