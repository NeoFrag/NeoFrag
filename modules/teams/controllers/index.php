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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_teams_c_index extends Controller_Module
{
	public function index()
	{
		$panels = array();
		
		foreach ($this->model()->get_teams() as $team)
		{
			$panel = array(
				'title'  => '<a href="'.$this->config->base_url.'teams/'.$team['team_id'].'/'.$team['name'].'.html">'.$team['title'].'</a>',
				'footer' => '{fa-icon users} '.$team['users'].' '.($team['users'] > 1 ? 'joueurs' : 'joueur'),
				'body'   => FALSE
			);
			
			if ($team['image_id'])
			{
				$panel['content'] = '<a href="'.$this->config->base_url.'teams/'.$team['team_id'].'/'.$team['name'].'.html"><img class="img-responsive" src="{image '.$team['image_id'].'}" alt="" /></a>';
			}
			
			if ($team['icon_id'] || $team['game_icon'])
			{
				$panel['title'] = '<img src="{image '.($team['icon_id'] ?: $team['game_icon']).'}" alt="" /> '.$panel['title'];
			}
			else
			{
				$panel['icon'] = 'fa-gamepad';
			}
			
			$panels[] = new Panel($panel);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel(array(
				'title'   => 'Équipes',
				'icon'    => 'fa-gamepad',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">Aucune équipe n\'a été créée pour le moment</div>'
			));
		}

		return $panels;
	}

	public function _team($team_id, $name, $title, $image_id, $icon_id, $description, $game_id, $game, $game_icon)
	{
		$this	->title($title)
				->load->library('table')
				->add_columns(array(
					array(
						'content' => '<img class="img-avatar-members" style="max-height: 40px; max-width: 40px;" src="<?php echo $NeoFrag->user->avatar($data[\'avatar\'], $data[\'sex\']); ?>" title="<?php echo $data[\'username\']; ?>" alt="" />',
						'size'    => TRUE
					),
					array(
						'content' => '<div><?php echo $NeoFrag->user->link($data[\'user_id\'], $data[\'username\']); ?></div><small><i class="fa fa-circle <?php echo $data[\'online\'] ? \'text-green\' : \'text-gray\'; ?>"></i> <?php echo $data[\'admin\'] ? \'Admin\' : \'Membre\'; ?> <?php echo $data[\'online\'] ? \'en ligne\' : \'hors ligne\'; ?></small>',
					),
					array(
						'content' => '{title}',
					)
				))
				->data($this->model()->get_players($team_id))
				->no_data('Il n\'y a pas encore de joueur dans cette équipe');
		
		$panel = array(
			'title' => '	<div class="pull-right">
								<span class="label label-default">'.$game.'</span>
							</div>
							<a href="'.$this->config->base_url.'teams/'.$team_id.'/'.$name.'.html">'.$title.'</a>',
			'body'  => FALSE
		);
		
		$panel['content'] = $this->load->view('index', array(
			'team_id'     => $team_id,
			'name'        => $name,
			'title'       => $title,
			'image_id'    => $image_id,
			'description' => bbcode($description),
			'users'       => $this->table->display()
		));
		
		if ($icon_id || $game_icon)
		{
			$panel['title'] = '<img src="{image '.($icon_id ?: $game_icon).'}" alt="" /> '.$panel['title'];
		}
		else
		{
			$panel['icon'] = 'fa-gamepad';
		}
		
		return array(
			new Panel($panel),
			new Button_back('teams.html')
		);
	}
}

/*
NeoFrag Alpha 0.1
./modules/teams/controllers/index.php
*/