<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		$panels = $this->array;

		foreach ($this->model()->get_teams() as $team)
		{
			$panel = $this->panel()	->heading($team['title'], $team['icon_id'] ?: $team['game_icon'] ?: 'fa-gamepad', 'teams/'.$team['team_id'].'/'.$team['name'])
									->footer(icon('fa-users').' '.$this->lang('%d joueur|%d joueurs', $team['users'], $team['users']));

			if ($team['image_id'])
			{
				$panel->body('<a href="'.url('teams/'.$team['team_id'].'/'.$team['name']).'"><img class="img-fluid" src="'.NeoFrag()->model2('file', $team['image_id'])->path().'" alt="" /></a>', FALSE);
			}

			$panels->append($panel);
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Équipe'), 'fa-gamepad')
									->body('<div class="text-center">'.$this->lang('Aucune équipe n\'a été créée pour le moment').'</div>')
									->color('info'));
		}

		return $panels;
	}

	public function _team($team_id, $name, $title, $image_id, $icon_id, $description, $game_id, $game, $game_icon)
	{
		$this->title($title);

		$players = $this->table()
						->add_columns([
							[
								'content' => function($data){
									return NeoFrag()->model2('user', $data['user_id'])->avatar();
								},
								'size'    => TRUE
							],
							[
								'content' => function($data){
									return '<div>'.NeoFrag()->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'Administrateur' : 'Membre').' '.$this->lang($data['online'] ? 'en ligne' : 'hors ligne').'</small>';
								}
							],
							[
								'content' => function($data){
									return $data['title'];
								}
							]
						])
						->data($this->model()->get_players($team_id))
						->no_data($this->lang('Il n\'y a pas encore de joueur dans cette équipe'))
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

		$matches = $this->table()
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
										return '<img src="'.NeoFrag()->model2('file', $data['match']['opponent']['image_id'])->path().'" style="max-height: 35px; max-width: 50px;" alt="" />';
									}
									else
									{
										return '';
									}
								},
								'class'   => 'col-1 text-center vcenter'
							],
							[
								'title'   => 'Adversaire',
								'content' => function($data){
									if ($data['match']['opponent']['country'])
									{
										$opponent = '<img src="'.url('themes/default/images/flags/'.$data['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$data['match']['opponent']['country']].'" style="margin-right: 8px;" alt="" />';
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
									<span class="badge badge-default">'.$game.'</span>
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
