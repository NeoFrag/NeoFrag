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

class m_teams_c_index extends Controller_Module
{
	public function index()
	{
		$panels = [];
		
		foreach ($this->model()->get_teams() as $team)
		{
			$panel = $this->panel()	->heading($team['title'], $team['icon_id'] ?: $team['game_icon'] ?: 'fa-gamepad', 'teams/'.$team['team_id'].'/'.$team['name'].'.html')
									->footer(icon('fa-users').' '.$this('player', $team['users'], $team['users']));

			if ($team['image_id'])
			{
				$panel->body('<a href="'.url('teams/'.$team['team_id'].'/'.$team['name'].'.html').'"><img class="img-responsive" src="'.path($team['image_id']).'" alt="" /></a>', FALSE);
			}

			$panels[] = $panel;
		}
		
		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this('teams'), 'fa-gamepad')
								->body('<div class="text-center">'.$this('no_team_yet').'</div>')
								->color('info');
		}

		return $panels;
	}

	public function _team($team_id, $name, $title, $image_id, $icon_id, $description, $game_id, $game, $game_icon)
	{
		$this	->title($title)
				->table
				->add_columns([
					[
						'content' => function($data){
							return NeoFrag::loader()->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']);
						},
						'size'    => TRUE
					],
					[
						'content' => function($data, $loader){
							return '<div>'.NeoFrag::loader()->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$loader->lang($data['admin'] ? 'admin' : 'member').' '.$loader->lang($data['online'] ? 'online' : 'offline').'</small>';
						},
					],
					[
						'content' => function($data){
							return $data['title'];
						},
					]
				])
				->data($this->model()->get_players($team_id))
				->no_data($this('no_players_on_team'));

		return [
			$this->panel()	->heading('	<div class="pull-right">
											<span class="label label-default">'.$game.'</span>
										</div>
										<a href="'.url('teams/'.$team_id.'/'.$name.'.html').'">'.$title.'</a>',
										$icon_id ?: $game_icon ?: 'fa-gamepad'
							)
							->body($this->view('index', [
								'team_id'     => $team_id,
								'name'        => $name,
								'title'       => $title,
								'image_id'    => $image_id,
								'description' => bbcode($description),
								'users'       => $this->table->display()
							]), FALSE),
			$this->panel_back('teams.html')
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/teams/controllers/index.php
*/