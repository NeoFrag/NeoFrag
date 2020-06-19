<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		$this->module('events')	->css('fullcalendar.min')
								->js('moment.min')
								->js('fullcalendar.min')
								->js('locale-all')
								->js('events');

		$this->css('events');

		return $this->panel()
					->heading('Calendrier', 'fas fa-calendar-alt')
					->body('<div id="calendar"></div>')
					->footer('<a href="'.url('events').'">'.icon('far fa-arrow-alt-circle-right').' Voir tous les événements</a>', 'right');
	}

	public function types($settings = [])
	{
		return $this->panel()
					->heading('Evénements', 'fas fa-calendar-alt')
					->body($this->view('types', [
						'types' => $this->model()->get_types()
					]), FALSE)
					->footer($this->view('filters'), 'left');
	}

	public function events($settings = [])
	{
		if (isset($settings['type_id']) && $settings['type_id'] != 0)
		{
			$type = $this->db	->from('nf_events_types')
								->where('type_id', $settings['type_id'])
								->row();

			$label  = $this->label($type['title'], $type['icon'], $type['color']);
			$events = $this->model()->get_events('type', $settings['type_id']);
		}
		else
		{
			$label = $this->label('Tous', '', 'light');
			$events = $this->model()->get_events();
		}

		return $this->panel()
					->heading('<div class="float-right">'.$label.'</div>Événements', 'fas fa-calendar-alt')
					->body($this->view('events', ['events' => array_slice($events, 0, 5)]), FALSE)
					->footer_if(count($events) > 5, '<a href="'.url('events').'">'.icon('far fa-arrow-alt-circle-right').' Voir tous les événements</a>', 'right');
	}

	public function event($settings = [])
	{
		$this->css('events');

		$event = $this->model()->check_event($settings['event_id']);
		$types = $this->model()->get_types();

		if ($types[$event['type_id']]['type'] == 1)
		{
			$icon = 'fas fa-crosshairs';
		}
		else
		{
			$icon = 'far fa-calendar';
		}

		$data = [
			'type'         => $types[$event['type_id']],
			'participants' => $this->module('events')->model('participants')->count_participants($event['event_id'])
		];

		if ($data['type']['type'] == 1 && ($match = $this->module('events')->model('matches')->get_match_info($event['event_id'])))//Matches
		{
			$data['match'] = $match;
		}

		if ($this->access('events', 'access_events_type', $data['type']['type_id']))
		{
			return $this->panel()
					->heading('<a href="'.url('events/'.$event['event_id'].'/'.url_title($event['title'])).'">'.$event['title'].'</a>', $icon)
					->body($this->view('event', array_merge($event, $data)), FALSE);
		}
	}

	public function matches($settings = [])
	{
		$this->css('events');

		if ($matches = $this->model()->get_events('filter', 'matches'))
		{
			foreach ($matches as $key => $match)
			{
				$matches[$key]['match'] = $this->module('events')->model('matches')->get_match_info($match['event_id']);

				if (!$this->access('events', 'access_events_type', $matches[$key]['type_id']))
				{
					unset($matches[$key]);
				}
			}
		}

		return $this->panel()
					->heading('Derniers résultats', 'fas fa-crosshairs')
					->body($this->view('matches', [
						'matches' => array_slice($matches, 0, 5)
					]), FALSE)
					->footer_if(!empty($matches), '<a href="'.url('events/matches').'">'.icon('fas fa-crosshairs').' Tous nos résultats</a>', 'right');
	}

	public function upcoming($settings = [])
	{
		$this->css('events');

		if ($matches = $this->model()->get_events('filter', 'upcoming'))
		{
			foreach ($matches as $key => $match)
			{
				$matches[$key]['match'] = $this->module('events')->model('matches')->get_match_info($match['event_id']);

				if (!$this->access('events', 'access_events_type', $matches[$key]['type_id']))
				{
					unset($matches[$key]);
				}
			}
		}

		return $this->panel()
					->heading('Prochains matchs', 'fas fa-crosshairs')
					->body($this->view('upcoming', [
						'matches' => array_slice($matches, 0, 5)
					]), FALSE)
					->footer_if(!empty($matches), '<a href="'.url('events/upcoming').'">'.icon('fas fa-crosshairs').' Voir les matchs à venir</a>', 'right');
	}
}
