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
			$panel = $this	->panel()
							->body($this->view('index', [
								'team_id'    => $team['team_id'],
								'name'       => $team['name'],
								'title'      => $team['title'],
								'icon_id'    => $team['icon_id'],
								'game_icon'  => $team['game_icon'],
								'game_title' => $team['game_title'],
								'image_id'   => $team['image_id'],
								'players'    => $this->model()->get_players($team['team_id']),
								'events'     => $this->_get_team_events($team['team_id'])
							]), FALSE)
							->footer_if($this->_check_team_recruits($team['team_id']), '<div class="text-info text-center font-weight-bold">'.$this->lang('Cette équipe recrute !').'</div>');

			$panels->append($panel);
		}

		if ($panels->empty())
		{
			$panels->append($this	->panel()
									->heading($this->lang('Équipe'), 'fas fa-headset')
									->body('<div class="text-center">'.$this->lang('Aucune équipe n\'a été créée pour le moment').'</div>')
									->color('info'));
		}

		return $panels;
	}

	public function _team($team_id, $name, $title, $image_id, $icon_id, $description, $game_id, $game, $game_icon)
	{
		$this	->title($title)
				->breadcrumb($title);

		if ($this->config->teams_display_matches && ($team_events = $this->_get_team_events($team_id)))
		{
			$matches = $this->table()
							->add_columns([
								[
									'title'   => 'Date',
									'content' => function($data){
										return timetostr('%d/%m/%Y', $data['date']);
									},
									'size'    => TRUE,
									'class'   => 'align-middle'
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
									'class'   => 'col-1 text-center align-middle'
								],
								[
									'title'   => 'Adversaire',
									'content' => function($data){
										if ($data['match']['opponent']['country'])
										{
											$opponent = '<img src="'.url('images/flags/'.$data['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$data['match']['opponent']['country']].'" style="margin-right: 8px;" alt="" />';
										}

										$opponent .= $data['match']['opponent']['title'];

										return $opponent;
									},
									'class'   => 'align-middle'
								],
								[
									'title'   => 'Événement',
									'content' => function($data){
										return '<a href="'.url('events/'.$data['event_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a>';
									},
									'class'   => 'align-middle'
								],
								[
									'title'   => '<div class="text-center">Score</div>',
									'content' => function($data){
										return $this->module('events')->model('matches')->display_scores($data['match']['scores'], $color).'<span class="'.$color.'">'.$data['match']['scores'][0].':'.$data['match']['scores'][1].'</span>';
									},
									'class'   => 'text-center align-middle'
								]
							])
							->data(array_slice($team_events, 0, 10))
							->no_data('Aucun match disputé...')
							->display();
		}

		return $this->array()
					->append(
						$this	->panel()
								->body($this->view('team', [
									'team_id'     => $team_id,
									'name'        => $name,
									'title'       => $title,
									'icon_id'     => $icon_id,
									'image_id'    => $image_id,
									'game'        => $game,
									'description' => bbcode($description),
									'players'     => $this->model()->get_players($team_id)
								]), FALSE)
								->footer_if($this->_check_team_recruits($team_id), '<div class="text-info text-center font-weight-bold">'.$this->lang('Cette équipe recrute !').'</div>')
					)
					->append_if(!empty($team_events), function() use ($team_events, $matches, $team_id, $name){
						return $this->panel()
									->heading('Derniers résultats', 'fas fa-crosshairs')
									->body($matches)
									->footer_if(count($team_events) > 10, '<a href="'.url('events/team/'.$team_id.'/'.url_title($name)).'">'.icon('far fa-arrow-alt-circle-right').' Voir tous les matchs de cette équipe</a>', 'right');
					})
					->append($this->panel_back('teams'));
	}

	public function _get_team_events($team_id)
	{
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

		return $team_matches ?: NULL;
	}

	public function _check_team_recruits($team_id)
	{
		$recruits = $this->db	->select('r.*', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined')
								->from('nf_recruits r')
								->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
								->group_by('r.recruit_id')
								->where('r.closed', FALSE)
								->where('r.team_id', $team_id)
								->get();

		if ($recruits && $this->module('recruits'))
		{
			foreach ($recruits as $recruit)
			{
				if ($recruit['closed'] || ($recruit['candidacies_accepted'] >= $recruit['size']) || ($recruit['date_end'] && strtotime($recruit['date_end']) < time()))
				{
					continue;
				}
				else
				{
					return TRUE;
					break;
				}
			}
		}

		return FALSE;
	}
}
