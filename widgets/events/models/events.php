<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Events\Models;

use NF\NeoFrag\Loadables\Model;

class Events extends Model
{
	public function check_event($event_id)
	{
		$this->db	->select('e.event_id', 'e.title', 'e.type_id', 'e.date', 'e.date_end', 'e.description', 'e.private_description', 'e.location', 'e.image_id', 'e.published', 't.type', 'm.webtv', 'm.website')
					->from('nf_events e')
					->join('nf_events_types t',        'e.type_id = t.type_id')
					->join('nf_events_participants p', 'e.event_id = p.event_id')
					->join('nf_events_matches m', 'e.event_id = m.event_id')
					->where('e.event_id', $event_id)
					->group_by('e.event_id');

		if (!$this->url->admin)
		{
			$this->db->where('e.published', TRUE);
		}

		$event = $this->db->row();

		if ($event && $this->access('events', 'access_events_type', $event['type_id']))
		{
			return $event;
		}
	}

	public function get_events($filter = '', $filter_data = '')
	{
		$types = array_keys($this->module('events')->model('types')->get_types());

		$this->db	->select('e.event_id', 'e.title', 'e.type_id', 't.title as type_title', 't.type', 't.color', 't.icon', 'e.date', 'e.date_end', 'e.description', 'e.private_description', 'e.location', 'e.image_id', 'e.published', 'u.id as user_id', 'u.username', 'COUNT(mr.round_id) as nb_rounds', 'm.webtv', 'm.website')
					->from('nf_events e')
					->join('nf_events_types t',           'e.type_id = t.type_id')
					->join('nf_events_participants p',    'e.event_id = p.event_id')
					->join('nf_user u',                   'u.id = e.user_id')
					->join('nf_events_matches m',         'e.event_id = m.event_id')
					->join('nf_events_matches_rounds mr', 'e.event_id = mr.event_id');

		if (!empty($filter) && !empty($filter_data))
		{
			if ($filter == 'filter')
			{
				if ($filter_data == 'standards')
				{
					$this->db->where('t.type', 0);
				}
				else if ($filter_data == 'matches')
				{
					$this->db	->where('t.type', 1)
								->having('nb_rounds > 0');
				}
				else if ($filter_data == 'upcoming')
				{
					$this->db	->where('t.type', 1)
								->having('nb_rounds = 0');
				}
			}
			else if ($filter == 'type')
			{
				$this->db->where('t.type_id', $filter_data);
			}
			else if ($filter == 'team')
			{
				$this->db->where('m.team_id', $filter_data);
			}
		}
		else
		{
			$this->db->where('t.type_id', $types, 'OR', 'p.user_id', $this->user->id);
		}

		if (!$this->url->admin)
		{
			$this->db->where('e.published', TRUE);
		}

		return $this->db->group_by('e.event_id')
						->order_by('date DESC')
						->get();
	}

	public function get_types()
	{
		static $types;

		if ($types === NULL)
		{
			$types = [];

			foreach (	$this->db	->select('t.*', 'COUNT(e.event_id) as nb_events')
									->from('nf_events_types t')
									->join('nf_events e', 't.type_id = e.type_id')
									->where('e.published', TRUE)
									->order_by('t.title')
									->group_by('t.type_id')
									->get()
			as $type)
			{
				if ($this->access('events', 'access_events_type', $type['type_id']))
				{
					$types[$type['type_id']] = $type;
				}
			}
		}

		return $types;
	}
}
