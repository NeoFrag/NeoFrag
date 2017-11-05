<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_teams_c_index extends Controller_Module
{
	public function index()
	{
		$panels = [];

		foreach ($this->model()->get_teams() as $team)
		{
			$panel = $this->panel()	->heading($team['title'], $team['icon_id'] ?: $team['game_icon'] ?: 'fa-gamepad', 'teams/'.$team['team_id'].'/'.$team['name'])
									->footer(icon('fa-users').' '.$this->lang('player', $team['users'], $team['users']));

			if ($team['image_id'])
			{
				$panel->body('<a href="'.url('teams/'.$team['team_id'].'/'.$team['name']).'"><img class="img-responsive" src="'.path($team['image_id']).'" alt="" /></a>', FALSE);
			}

			$panels[] = $panel;
		}

		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('teams'), 'fa-gamepad')
								->body('<div class="text-center">'.$this->lang('no_team_yet').'</div>')
								->color('info');
		}

		return $panels;
	}

	public function _team($team_id, $name, $title, $image_id, $icon_id, $description, $game_id, $game, $game_icon)
	{
		$this->title($title);

		$players = $this->table
						->add_columns([
							[
								'content' => function($data){
									return NeoFrag()->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']);
								},
								'size'    => TRUE
							],
							[
								'content' => function($data){
									return '<div>'.NeoFrag()->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'admin' : 'member').' '.$this->lang($data['online'] ? 'online' : 'offline').'</small>';
								}
							],
							[
								'content' => function($data){
									return $data['title'];
								}
							]
						])
						->data($this->model()->get_players($team_id))
						->no_data($this->lang('no_players_on_team'))
						->display();

		$events = $this->module('events') ? $this->module('events')->model()->get_events('team', $team_id) : NULL;

		$team_matches = [];
		foreach ($events as $key => $event)
		{
			if ($event['nb_rounds'] > 0)
			{
				$team_matches[$key] = $event;
				$team_matches[$key]['match'] = $this->module('events')->model('matches')->get_match_info($event['event_id']);
			}
		}

		array_slice($events, 0, 10);

		$matches = $this->table
						->add_columns([
							[
								'title'   => 'Date',
								'content' => function($data){
									return timetostr('%d/%m/%Y', $data['date']);
								},
								'size'    => TRUE,
								'class'   => 'vcenter'
							],
							[
								'content' => function($data){
									if ($data['match']['opponent']['image_id'])
									{
										return '<img src="'.path($data['match']['opponent']['image_id']).'" style="max-height: 35px; max-width: 50px;" alt="" />';
									}
									else
									{
										return '';
									}
								},
								'class'   => 'col-md-1 text-center vcenter'
							],
							[
								'title'   => 'Adversaire',
								'content' => function($data){
									if ($data['match']['opponent']['country'])
									{
										$opponent = '<img src="'.url('neofrag/themes/default/images/flags/'.$data['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$data['match']['opponent']['country']].'" style="margin-right: 8px;" alt="" />';
									}

									$opponent .= $data['match']['opponent']['title'];

									return $opponent;
								},
								'class'   => 'vcenter'
							],
							[
								'title'   => 'Événement',
								'content' => function($data){
									return '<a href="'.url('events/'.$data['event_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a>';
								},
								'class'   => 'vcenter'
							],
							[
								'title'   => '<div class="text-center">Score</div>',
								'content' => function($data){
									return $this->module('events')->model('matches')->display_scores($data['match']['scores'], $color).'<span class="'.$color.'">'.$data['match']['scores'][0].':'.$data['match']['scores'][1].'</span>';
								},
								'class'   => 'text-center vcenter'
							]
						])
						->data($team_matches)
						->no_data('Aucun match disputé...')
						->display();

		return [
			$this	->panel()
					->heading('	<div class="pull-right">
									<span class="label label-default">'.$game.'</span>
								</div>
								<a href="'.url('teams/'.$team_id.'/'.$name).'">'.$title.'</a>',
								$icon_id ?: $game_icon ?: 'fa-gamepad'
					)
					->body($this->view('index', [
						'team_id'     => $team_id,
						'name'        => $name,
						'title'       => $title,
						'image_id'    => $image_id,
						'description' => bbcode($description),
						'users'       => $players
					]), FALSE),
			$team_matches ? $this->panel()
					->heading('Derniers résultats', 'fa-crosshairs')
					->body($matches)
					->footer_if((count($team_matches) > 10), '<a href="'.url('events/team/'.$team_id.'/'.url_title($name)).'">'.icon('fa-arrow-circle-o-right').' Voir tous les matchs de cette équipe</a>', 'right') : NULL,
			$this	->panel_back('teams')
		];
	}
}
