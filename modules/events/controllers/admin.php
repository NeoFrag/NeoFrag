<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($events)
	{
		$this	->css('fullcalendar.min')
				->css('admin')
				->js('moment.min')
				->js('fullcalendar.min')
				->js('lang-all')
				->js('events');

		$types = $this	->table()
						->add_columns([
							[
								'content' => function($data){
									return $this->label($data['title'], $data['icon'], $data['color'], 'admin/events/types/'.$data['type_id'].'/'.url_title($data['title']));
								}
							],
							[
								'content' => [
									function($data){
										return $this->user->admin ? $this->button_access($data['type_id'], 'type') : NULL;
									},
									function($data){
										return $this->is_authorized('modify_events_type') ? $this->button_update('admin/events/types/'.$data['type_id'].'/'.url_title($data['title'])) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_events_type') ? $this->button_delete('admin/events/types/delete/'.$data['type_id'].'/'.url_title($data['title'])) : NULL;
									}
								],
								'size'    => TRUE
							]
						])
						->pagination(FALSE)
						->data($this->model('types')->get_types())
						->no_data('Aucun type')
						->display();

		$events = $this	->table()
						->add_columns([
							[
								'content' => function($data){
									return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$this->lang('Publié').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$this->lang('En attente de publication').'" style="color: #535353;"></i>';
								},
								'sort'    => function($data){
									return $data['published'];
								},
								'size'    => TRUE
							],
							[
								'title'   => 'Type',
								'content' => function($data){
									return $this->label($data['type_title'], $data['icon'], $data['color'], 'admin/events/types/'.$data['type_id'].'/'.url_title($data['type_title']));
								},
								'sort'    => function($data){
									return $data['type_title'];
								},
								'search'  => function($data){
									return $data['type_title'];
								}
							],
							[
								'title'   => 'Titre',
								'content' => function($data){
									return '<a href="'.url('events/'.$data['event_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a>';
								},
								'sort'    => function($data){
									return $data['title'];
								},
								'search'  => function($data){
									return $data['title'];
								}
							],
							[
								'content' => function($data){
									if ($data['type'] == 1 && ($match = $this->model('matches')->get_match_info($data['event_id'])))//Matches
									{
										return  ($match['scores'] ? $this->model('matches')->label_global_scores($data['event_id']).'<span style="margin: 0 10px;"> vs </span>' : '<span style="margin-right: 10px;">'.$this->lang('Match à jouer vs ').'</span>').
												($match['opponent']['country'] ? '<img src="'.url('themes/default/images/flags/'.$match['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['opponent']['country']].'" style="margin-right: 10px;" alt="" />' : '').
												$match['opponent']['title'].' <i>('.$match['game']['title'].')</i>';
									}
								},
								'sort'    => function($data){
									if ($data['type'] == 1 && ($match = $this->model('matches')->get_match_info($data['event_id'])))//Matches
									{
										return $match['opponent']['title'].' '.$match['game']['title'].' '.implode(' - ', $match['scores']);
									}
								},
								'search'  => function($data){
									if ($data['type'] == 1 && ($match = $this->model('matches')->get_match_info($data['event_id'])))//Matches
									{
										return $match['opponent']['title'].' '.$match['game']['title'].' '.implode(' - ', $match['scores']);
									}
								}
							],
							[
								'title'   => $this->lang('Auteur'),
								'content' => function($data){
									return $data['user_id'] ? NeoFrag()->user->link($data['user_id'], $data['username']) : $this->lang('Visiteur');
								},
								'sort'    => function($data){
									return $data['username'];
								},
								'search'  => function($data){
									return $data['username'];
								}
							],
							[
								'title'   => $this->lang('Date'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.timetostr(NeoFrag()->lang('%d/%m/%Y %H:%M'), $data['date']).($data['date_end'] ? '&nbsp;&nbsp;<i>'.icon('fa-hourglass-end').(ceil((strtotime($data['date_end']) - strtotime($data['date'])) / ( 60 * 60 ))).'h</i>' : '').'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							],
							[
								'title'   => '<i class="fa fa-users" data-toggle="tooltip" title="'.$this->lang('Participants').'"></i>',
								'content' => function($data){
									return '<a href="'.url('events/'.$data['event_id'].'/'.url_title($data['title']).'#participants').'">'.$this->model('participants')->count_participants($data['event_id']).'</a>';
								},
								'size'    => TRUE
							],
							[
								'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="'.$this->lang('Commentaires').'"></i>',
								'content' => function($data){
									return $this->module('comments')->admin('events', $data['event_id']);
								},
								'size'    => TRUE
							],
							[
								'content' => [
									function($data){
										return $this->is_authorized('modify_event') ? $this->button_update('admin/events/'.$data['event_id'].'/'.url_title($data['title'])) : NULL;
									},
									function($data){
										return $this->is_authorized('delete_event') ? $this->button_delete('admin/events/delete/'.$data['event_id'].'/'.url_title($data['title'])) : NULL;
									}
								],
								'size'    => TRUE
							]
						])
						->data($events)
						->no_data($this->lang('Il n\'y a pas encore d\'événement'))
						->display();

		return $this->row(
			$this	->col(
						$this	->panel()
								->heading('', 'fa-calendar')
								->body('<div id="calendar"></div>', FALSE),
						$this	->panel()
								->heading($this->lang('Types d\'événement'), 'fa-bookmark-o')
								->body($types)
								->footer($this->is_authorized('add_events_type') ? $this->button_create('admin/events/types/add', $this->lang('Créer un type d\'événement')) : NULL)
					)
					->size('col-4 col-lg-3'),
			$this	->col(
						$this	->panel()
								->heading($this->lang('Liste des événements'), 'fa-calendar')
								->body('<div class="panel-footer">'.$this->_filters().'</div><div class="panel-body">'.$events.'</div>', FALSE)
								->footer($this->is_authorized('add_event') ? $this->button_create('admin/events/add', $this->lang('Créer un événement')) : NULL)
					)
					->size('col-8 col-lg-9')
		);
	}

	public function standards($events)
	{
		return $this->index($events);
	}

	public function matches($events)
	{
		return $this->index($events);
	}

	public function upcoming($events)
	{
		return $this->index($events);
	}

	public function _filters()
	{
		return $this->view('filters', ['type' => '']);
	}

	public function add()
	{
		$this	->subtitle($this->lang('Ajouter un événement'))
				->form()
				->add_rules('events')
				->add_submit($this->lang('Ajouter'))
				->add_back('admin/events');

		if ($this->form()->is_valid($post))
		{
			$event_id = $this->model()->add($post['title'],
											$post['type'],
											$post['date'],
											$post['date_end'],
											$post['description'],
											$post['private_description'],
											$post['location'],
											$post['image'],
											in_array('on', $post['published']));

			notify($this->lang('Événement ajouté'));

			if ($this->db->select('type')->from('nf_events_types')->where('type_id', $post['type'])->row())
			{
				redirect('admin/events/'.$event_id.'/'.url_title($post['title']));
			}
			else
			{
				redirect_back('admin/events');
			}
		}

		return $this->panel()
					->heading($this->lang('Ajouter un événement'), 'fa-calendar')
					->body($this->form()->display());
	}

	public function _edit($event_id, $title, $type_id, $date, $date_end, $description, $private_description, $location, $image_id, $published, $type)
	{
		$form_default = $this	->title($this->lang('Éditer l\'événement'))
								->subtitle($title)
								->form()
								->add_rules('events', [
									'title'               => $title,
									'type_id'             => $type_id,
									'image_id'            => $image_id,
									'description'         => $description,
									'private_description' => $private_description,
									'location'            => $location,
									'date'                => $date,
									'date_end'            => $date_end,
									'published'           => $published
								])
								->add_submit($this->lang('Éditer'))
								->add_back('admin/events')
								->save();

		if ($type == 1)//Matches
		{
			$match = $this->db->from('nf_events_matches')->where('event_id', $event_id)->row();

			if (!empty($match['mode_id']))
			{
				$game_id = $this->db->select('game_id')->from('nf_games_modes')->where('mode_id', $match['mode_id'])->row();
				$this->db->where('game_id', $game_id);
			}

			$maps = [];

			foreach ($this->db->select('*')->from('nf_games_maps')->get() as $map)
			{
				$maps[$map['map_id']] = $map['title'];
			}

			$form_match = $this	->form()
								->add_rules([
									'team' => [
										'label'       => $this->lang('Équipe'),
										'value'       => isset($match['team_id']) ? $match['team_id'] : NULL,
										'values'      => $this->module('teams')->model()->get_teams_list(),
										'type'        => 'select',
										'rules'       => $this->lang('required')
									],
									'opponent' => [
										'label'       => $this->lang('Adversaire'),
										'value'       => isset($match['opponent_id']) ? $match['opponent_id'] : NULL,
										'values'      => $this->model('matches')->get_opponents_list(),
										'type'        => 'select',
										'rules'       => $this->lang('required')
									],
									'mode' => [
										'label'       => $this->lang('Mode'),
										'value'       => isset($match['mode_id']) ? $match['mode_id'] : NULL,
										'values'      => $this->module('games')->model('modes')->get_modes_list(),
										'type'        => 'select'
									],
									'webtv' => [
										'label'       => $this->lang('WebTv'),
										'value'       => isset($match['webtv']) ? $match['webtv'] : NULL,
										'description' => $this->lang('Renseignez l\'url de votre chaine Twitch pour indiquer une retransmission en Live.'),
										'type'        => 'url'
									],
									'website' => [
										'label'       => $this->lang('Site web'),
										'value'       => isset($match['website']) ? $match['website'] : NULL,
										'description' => $this->lang('Renseignez un site qui parle de l\'événement'),
										'type'        => 'url'
									]
								])
								->add_submit($this->lang('Valider'))
								->save();

			$form_opponent = $this	->form()
									->add_rules([
										'title' => [
											'label'       => $this->lang('Nom'),
											'rules'       => $this->lang('required')
										],
										'image' => [
											'label'       => $this->lang('Image'),
											'type'        => 'file',
											'upload'      => 'opponents',
											'info'        => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
											'check'       => function($filename, $ext){
												if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
												{
													return $this->lang('Veuiller choisir un fichier d\'image');
												}
											}
										],
										'country' => [
											'label'       => $this->lang('Pays'),
											'values'      => get_countries(),
											'type'        => 'select'
										],
										'website' => [
											'label'       => $this->lang('Site web'),
											'type'        => 'url'
										]
									])
									->add_submit($this->lang('Valider'))
									->save();

			$form_round = $this	->form()
								->add_rules([
									'map' => [
										'label'  => $this->lang('Carte'),
										'type'   => 'select',
										'values' => $maps,
										'size'   => 'col-5'
									],
									'score1' => [
										'label'  => $this->lang('Notre score'),
										'type'   => 'number',
										'rules'  => $this->lang('required'),
										'size'   => 'col-3'
									],
									'score2' => [
										'label'  => $this->lang('Score adverse'),
										'type'   => 'number',
										'rules'  => $this->lang('required'),
										'size'   => 'col-3'
									]
								])
								->add_submit($this->lang('Valider'))
								->save();

			if ($form_match->is_valid($post))
			{
				$this->db->replace('nf_events_matches', [
					'event_id'    => $event_id,
					'team_id'     => $post['team'],
					'opponent_id' => $post['opponent'],
					'mode_id'     => isset($post['mode']) ? $post['mode'] : NULL,
					'webtv'       => $post['webtv'],
					'website'     => $post['website']
				]);

				notify($this->lang('Rencontre éditée'));

				redirect('admin/events/'.$event_id.'/'.url_title($title));
			}
			else if ($form_opponent->is_valid($post))
			{
				$this->db->insert('nf_events_matches_opponents', [
					'image_id' => $post['image'],
					'title'    => $post['title'],
					'country'  => $post['country'],
					'website'  => $post['website']
				]);

				notify($this->lang('Adversaire ajouté'));

				redirect('admin/events/'.$event_id.'/'.url_title($title));
			}
			else if ($form_round->is_valid($post))
			{
				$this->db->insert('nf_events_matches_rounds', [
					'event_id' => $event_id,
					'map_id'   => !empty($post['map']) ? $post['map'] : NULL,
					'score1'   => $post['score1'],
					'score2'   => $post['score2']
				]);

				notify($this->lang('Manche ajoutée'));

				redirect('admin/events/'.$event_id.'/'.url_title($title));
			}
		}

		if ($form_default->is_valid($post))
		{
			$this->model()->edit(	$event_id,
									$post['title'],
									$post['type'],
									$post['date'],
									$post['date_end'],
									$post['description'],
									$post['private_description'],
									$post['location'],
									$post['image'],
									in_array('on', $post['published']));

			notify($this->lang('Événement édité'));

			$new_type = $this->db->select('type')->from('nf_events_types')->where('type_id', $post['type'])->row();

			if ($new_type && $type != $new_type)
			{
				redirect('admin/events/'.$event_id.'/'.url_title($post['title']));
			}
			else
			{
				redirect_back('admin/events');
			}
		}

		$alert = '';

		if ($published && !$this->model('participants')->get_participants($event_id))
		{
			$alert = $this	->panel()
							->body('<div class="pull-right"><a href="'.url('events/'.$event_id.'/'.url_title($title).'#participants').'" class="btn btn-info">'.$this->lang('Inviter des membres').'</a></div><i class="fa fa-info-circle"></i> <b>'.$this->lang('Pense-bête !').'</b><br />'.$this->lang('N\'oubliez pas d\'envoyer vos demandes de participation à vos membres !').'</b>')
							->color('info');
		}

		$panel = $this		->panel()
							->heading($this->lang('Éditer l\'événement'), 'fa-align-left')
							->body($form_default->display());

		if ($type == 1)//Matches
		{
			$this	->table()
					->add_columns([
						[
							'content' => function($data){
								return $this->model('matches')->label_scores($data['score1'], $data['score2']).($data['title'] ? ' ('.$data['title'].')' : '');
							}
						],
						[
							'content' => [
								function($data) use ($event_id, $title){
									return $this->button_delete('admin/events/rounds/delete/'.$event_id.'/'.url_title($title).'/'.$data['round_id']);
								}
							],
							'size'    => TRUE
						]
					])
					->pagination(FALSE)
					->data($rounds = $this->db	->select('r.round_id', 'm.title', 'r.score1', 'r.score2')
												->from('nf_events_matches_rounds r')
												->join('nf_games_maps m', 'm.map_id = r.map_id')
												->where('r.event_id', $event_id)
												->order_by('r.round_id')
												->get())
					->no_data($this->lang('Aucune manche renseignée'));

			$modal_opponent = $this	->modal($this->lang('Ajouter un adversaire'), 'fa-plus')
									->body($form_opponent->display())
									->open_if($form_opponent->get_errors());

			$modal_round    = $this	->modal($this->lang('Ajouter une manche'), 'fa-plus')
									->body($form_round->display())
									->open_if($form_round->get_errors());

			return $this->row(
				$this	->col($alert, $panel)
						->size('col-8'),
				$this	->col(
							$this	->panel()
									->heading($this->lang('Détails de la rencontre').'<div class="pull-right">'.$this->button()->title($this->lang('Ajouter un adversaire'))->icon('fa-plus')->modal($modal_opponent).'</div>', 'fa-info-circle')
									->body($form_match->display()),
							$this	->panel()
									->heading($this->lang('Manches jouées').(count($rounds) > 1 ? '<div class="pull-right">'.$this->lang('Résultat global').' '.$this->model('matches')->label_global_scores($event_id).'</div>' : ''), 'fa-gamepad')
									->body($this->table()->display())
									->footer($this->button_create('#', $this->lang('Ajouter une manche'))->modal($modal_round))
						)
						->size('col-4')
			);
		}
		else
		{
			return $panel;
		}
	}

	public function delete($event_id, $title)
	{
		$this	->title($this->lang('Suppression événement'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le l\'événement').' <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->model()->delete($event_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _types_add()
	{
		$this	->subtitle($this->lang('Ajouter un type d\'événement'))
				->form()
				->add_rules('types')
				->add_back('admin/events')
				->add_submit($this->lang('Ajouter'));

		if ($this->form()->is_valid($post))
		{
			$this->model('types')->add(	$post['type'],
										$post['title'],
										$post['color'],
										$post['icon']);

			notify($this->lang('Type d\'événement ajouté'));

			redirect_back('admin/events');
		}

		return $this->panel()
					->heading($this->lang('Ajouter un type d\'événement'), 'fa-bookmark-o')
					->body($this->form()->display());
	}

	public function _types_edit($type_id, $type, $title, $color, $icon)
	{
		$this	->subtitle($this->lang('Type').' '.$title)
				->form()
				->add_rules('types', [
					'type'  => $type,
					'title' => $title,
					'color' => $color,
					'icon'  => $icon
				])
				->add_submit($this->lang('Éditer'))
				->add_back('admin/events');

		if ($this->form()->is_valid($post))
		{
			$this->model('types')->edit($type_id,
										$post['type'],
										$post['title'],
										$post['color'],
										$post['icon']);

			notify($this->lang('Type d\'événement édité'));

			redirect_back('admin/events');
		}

		return $this->panel()
					->heading($this->lang('Éditer le type d\'événement'), 'fa-bookmark-o')
					->body($this->form()->display());
	}

	public function _types_delete($type_id, $title)
	{
		$this	->title($this->lang('Suppression type d\'événement'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le type d\'événement').' <b>'.$title.'</b> ?<br />'.$this->lang('Tous les événements de ce type seront aussi supprimés.'));

		if ($this->form()->is_valid())
		{
			$this->model('types')->delete($type_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _round_delete($round_id)
	{
		$this	->title($this->lang('Suppression manche'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer cette manche ?'));

		if ($this->form()->is_valid())
		{
			$this->db	->where('round_id', $round_id)
						->delete('nf_events_matches_rounds');

			return 'OK';
		}

		return $this->form()->display();
	}
}
