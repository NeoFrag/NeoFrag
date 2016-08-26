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
			$panel = [
				'title'  => '<a href="'.url('teams/'.$team['team_id'].'/'.$team['name'].'.html').'">'.$team['title'].'</a>',
				'footer' => icon('fa-users').' '.$this('player', $team['users'], $team['users']),
				'body'   => FALSE
			];
			
			if ($team['image_id'])
			{
				$panel['content'] = '<a href="'.url('teams/'.$team['team_id'].'/'.$team['name'].'.html').'"><img class="img-responsive" src="'.path($team['image_id']).'" alt="" /></a>';
			}
			
			if ($team['icon_id'] || $team['game_icon'])
			{
				$panel['title'] = '<img src="'.path($team['icon_id'] ?: $team['game_icon']).'" alt="" /> '.$panel['title'];
			}
			else
			{
				$panel['icon'] = 'fa-gamepad';
			}
			
			$panels[] = new Panel($panel);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel([
				'title'   => $this('teams'),
				'icon'    => 'fa-gamepad',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">'.$this('no_team_yet').'</div>'
			]);
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
		
		$panel = [
			'title' => '	<div class="pull-right">
								<span class="label label-default">'.$game.'</span>
							</div>
							<a href="'.url('teams/'.$team_id.'/'.$name.'.html').'">'.$title.'</a>',
			'body'  => FALSE
		];
		
		$panel['content'] = $this->load->view('index', [
			'team_id'     => $team_id,
			'name'        => $name,
			'title'       => $title,
			'image_id'    => $image_id,
			'description' => bbcode($description),
			'users'       => $this->table->display()
		]);
		
		if ($icon_id || $game_icon)
		{
			$panel['title'] = '<img src="'.path($icon_id ?: $game_icon).'" alt="" /> '.$panel['title'];
		}
		else
		{
			$panel['icon'] = 'fa-gamepad';
		}
		
		return [
			new Panel($panel),
			new Button_back('teams.html')
		];
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/teams/controllers/index.php
*/