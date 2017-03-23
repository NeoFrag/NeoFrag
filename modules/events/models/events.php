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

class m_events_m_events extends Model
{
	public function check_event($event_id, $title)
	{
		$this->db	->select('e.event_id', 'e.title', 'e.type_id', 'e.date', 'e.date_end', 'e.description', 'e.private_description', 'e.location', 'e.image_id', 'e.published', 't.type', 'm.mode_id', 'm.webtv', 'm.website', 'gm.title as mode_title')
					->from('nf_events e')
					->join('nf_events_types t',        'e.type_id = t.type_id')
					->join('nf_events_participants p', 'e.event_id = p.event_id')
					->join('nf_events_matches m', 'e.event_id = m.event_id')
					->join('nf_games_modes gm', 'm.mode_id = gm.mode_id')
					->where('e.event_id', $event_id)
					->group_by('e.event_id');

		if (!$this->url->admin)
		{
			$this->db->where('e.published', TRUE);
		}

		$event = $this->db->row();

		if ($event && $title == url_title($event['title']) && $this->access('events', 'access_events_type', $event['type_id']))
		{
			return $event;
		}
	}

	public function get_events($filter = '', $filter_data = '')
	{
		$types = array_keys($this->model('types')->get_types());

		$this->db	->select('e.event_id', 'e.title', 'e.type_id', 't.title as type_title', 't.type', 't.color', 't.icon', 'e.date', 'e.date_end', 'e.description', 'e.private_description', 'e.location', 'e.image_id', 'e.published', 'u.user_id', 'u.username', 'COUNT(mr.round_id) as nb_rounds', 'm.webtv', 'm.website')
					->from('nf_events e')
					->join('nf_events_types t',           'e.type_id = t.type_id')
					->join('nf_events_participants p',    'e.event_id = p.event_id')
					->join('nf_users u',                  'u.user_id = e.user_id')
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
			$this->db->where('t.type_id', $types, 'OR', 'p.user_id', $this->user('user_id'));
		}

		if (!$this->url->admin)
		{
			$this->db->where('e.published', TRUE);
		}

		return $this->db->group_by('e.event_id')
						->order_by('date DESC')
						->get();
	}

	public function add($title, $type_id, $date, $date_end, $description, $private_description, $location, $image_id, $published)
	{
		return $this->db->insert('nf_events', [
			'user_id'             => $this->user('user_id'),
			'title'               => $title,
			'type_id'             => $type_id,
			'date'                => $date,
			'date_end'            => $date_end ?: NULL,
			'description'         => $description,
			'private_description' => $private_description,
			'location'            => $location,
			'image_id'            => $image_id,
			'published'           => $published
		]);
	}

	public function edit($event_id, $title, $type_id, $date, $date_end, $description, $private_description, $location, $image_id, $published)
	{
		$this->db	->where('event_id', $event_id)
					->update('nf_events', [
						'title'               => $title,
						'type_id'             => $type_id,
						'date'                => $date,
						'date_end'            => $date_end ?: NULL,
						'description'         => $description,
						'private_description' => $private_description,
						'location'            => $location,
						'image_id'            => $image_id,
						'published'           => $published
					]);
	}

	public function delete($event_id)
	{
		$this	->file		->delete($this->db->select('image_id')->from('nf_events')->where('event_id', $event_id)->row())
				->comments	->delete('events', $event_id);

		$this->db	->where('event_id', $event_id)
					->delete('nf_events');
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/events/models/events.php
*/