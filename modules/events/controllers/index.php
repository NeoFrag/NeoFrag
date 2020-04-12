<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index($events)
	{
		$panels = $this->_filters();

		$types = $this->model('types')->get_types();

		foreach ($events as $event)
		{
			if ($types[$event['type_id']]['type'] == 1)//Matches
			{
				$icon = 'fas fa-crosshairs';
			}
			else
			{
				$icon = 'far fa-calendar';
			}

			$data = [
				'type'         => $types[$event['type_id']],
				'participants' => $this->model('participants')->count_participants($event['event_id'])
			];

			if ($data['type']['type'] == 1 && ($match = $this->model('matches')->get_match_info($event['event_id'])))//Matches
			{
				$data['match'] = $match;
			}

			if ($this->access('events', 'access_events_type', $event['type_id']))
			{
				$panels->append($this	->panel()
										->heading('<a href="'.url('events/'.$event['event_id'].'/'.url_title($event['title'])).'">'.$event['title'].'</a>'.(!empty($data['match']) ? '<div class="float-right">'.($data['match']['game']['icon_id'] ? '<img src="'.NeoFrag()->model2('file', $data['match']['game']['icon_id'])->path().'" class="img-icon" alt="" />' : icon('fas fa-gamepad')).' '.$data['match']['game']['title'].'</div>' : ''), $icon)
										->body($this->view('event', array_merge($event, $data)), FALSE));
			}
		}

		if (!$events)
		{
			$panels->append($this	->panel()
									->heading()
									->body('<div class="text-center">Aucun événement n\'a été publiée pour le moment</div>')
									->color('info'));
		}
		else
		{
			$panels->append($this->module->pagination->panel());
		}

		return $panels;
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

	public function _type($events)
	{
		return $this->index($events);
	}

	public function _team($events)
	{
		return $this->index($events);
	}

	public function _filters()
	{
		$type = '';

		if (isset($this->url->segments[1]) && $this->url->segments[1] == 'type')
		{
			$type = $this->model('types')->check_type($this->url->segments[2], $this->url->segments[3]);
		}

		return $this->array
					->append($this	->panel()
									->body($this->view('filters', [
										'type' => $type
									]))
					);
	}

	public function _event($event_id, $title, $type_id, $date, $date_end, $description, $private_description, $location, $image_id, $published, $type, $mode_id, $webtv, $website, $mode_title)
	{
		$this	->title($title)
				->breadcrumb($title)
				->table()
				->add_columns([
					[
						'content' => function($data){
							return $this->module('user')->model2('user', $data['user_id'])->avatar();
						},
						'size'    => TRUE
					],
					[
						'content' => function($data){
							return '<div>'.$this->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fas fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.($data['admin'] ? 'Admin' : 'Membre').' '.($data['online'] ? 'en ligne' : 'hors ligne').'</small>';
						}
					],
					[
						'align'   => 'right',
						'content' => function($data) use ($event_id, $title){
							return $data['user_id'] == $this->user->id ? $this->model('participants')->buttons_status($event_id, $title, $data['status']) : $this->model('participants')->label_status($data['status']);
						}
					]
				])
				->add_columns_if($this->user->admin, [[
						'content' => function($data) use ($event_id, $title){
							return $this->button_delete('events/participant/delete/'.$event_id.'/'.url_title($title).'/'.$data['user_id']);
						},
						'size'    => TRUE
					]
				])
				->data($this->model('participants')->get_participants($event_id))
				->no_data('Aucun participant pour cet événement');

		$match = $type == 1 ? $this->model('matches')->get_match_info($event_id) : NULL;

		$rounds = $this->db	->select('r.round_id', 'm.image_id', 'm.title', 'r.score1', 'r.score2')
							->from('nf_events_matches_rounds r')
							->join('nf_games_maps m', 'm.map_id = r.map_id')
							->where('r.event_id', $event_id)
							->order_by('r.round_id')
							->get();

		if ($this->user->admin)
		{
			$this->js('participants');

			$participants = $this->db	->select('user_id')
										->from('nf_events_participants')
										->where('event_id', $event_id)
										->get();

			$users = [];

			foreach ($this->db->select('id', 'username')->from('nf_user')->where_if($participants, 'id NOT', $participants)->where('deleted', FALSE)->get() as $user)
			{
				if ($this->access('events', 'access_events_type', $type_id, NULL, $user['id']))
				{
					$users[$user['id']] = $user['username'];
				}
			}

			array_natsort($users);

			$this	->form()
					->add_rules([
						'users' => [
							'type'   => 'checkbox',
							'values' => $users,
							'rules'  => 'required'
						]
					]);

			if ($this->form()->is_valid($post))
			{
				$this->model('participants')->invite($event_id, $title, array_unique($post['users']));

				notify('Invitations envoyées');

				refresh();
			}

			$modal = $this	->modal('Inviter des membres', 'fas fa-user-plus')
							->body($this->view('participants', [
								'users'   => $users,
								'form_id' => $this->form()->token()
							]))
							->submit('Inviter')
							->cancel()
							->set_id('c2dac90bb0731401a293d27ee036757a')
							->callback(function(){});
		}

		return $this->_filters()
					->append($this	->panel()
									->heading('<a href="'.url('events/'.$event_id.'/'.url_title($title)).'">'.$title.'</a>'.(!empty($match) ? '<div class="float-right">'.($match['game']['icon_id'] ? '<img src="'.NeoFrag()->model2('file', $match['game']['icon_id'])->path().'" class="img-icon" alt="" />' : icon('fas fa-gamepad')).' '.$match['game']['title'].'</div>' : ''), $type == 1 ? 'fas fa-crosshairs' : 'far fa-calendar')
									->body($this->view('event', [
										'event_id'             => $event_id,
										'title'                => $title,
										'date'                 => $date,
										'date_end'             => $date_end,
										'description'          => $description,
										'private_description'  => $private_description,
										'location'             => $location,
										'image_id'             => $image_id,
										'match'                => $match,
										'webtv'                => $webtv,
										'website'              => $website,
										'mode'                 => $mode_title,
										'rounds'               => $rounds,
										'type'                 => $this->model('types')->get_types()[$type_id],
										'participants'         => $this->model('participants')->count_participants($event_id),
										'list_participants'    => $this->model('participants')->get_participants($event_id),
										'show_details'         => TRUE
									]), FALSE)
					)
					->append_if($this->user(), $this	->panel()
														->heading('<a name="participants"></a>Participants'.(isset($modal) ? '<div class="float-right">'.$this->button()->title('Invitations')->icon('fas fa-user-plus')->modal($modal).'</div>' : ''), 'fas fa-users')
														->body($this->table()->display())
					)
					->append_if(($comments = $this->module('comments')) && $comments->is_enabled(), function() use (&$comments, $event_id){
						return $comments('events', $event_id);
					})
					->append($this->button_back());
	}

	public function _participant_add($event_id, $title, $status)
	{
		$this->db	->where('event_id', $event_id)
					->where('user_id', $this->user->id)
					->update('nf_events_participants', [
						'status' => $status
					]);

		notify('Disponibilité ajoutée');

		redirect('events/'.$event_id.'/'.$title.'#participants');
	}

	public function _participant_delete($event_id, $user_id)
	{
		$this	->title('Suppression participant')
				->form()
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer cet invité ?');

		if ($this->form()->is_valid())
		{
			$this->db	->where('event_id', $event_id)
						->where('user_id', $user_id)
						->delete('nf_events_participants');

			return 'OK';
		}

		return $this->form()->display();
	}
}
