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

class m_events_c_ajax extends Controller_Module
{
	public function index()
	{
		$this->extension('json');

		$types  = array_keys($this->model('types')->get_types());
		$events = [];

		foreach ($this->db	->select('e.event_id as id', 'e.title', 't.color', 't.icon', 'e.date as start', 'e.date_end as end')
							->from('nf_events e')
							->join('nf_events_types t', 'e.type_id = t.type_id')
							->join('nf_users u',        'u.user_id = e.user_id')
							->where('(date >=',    $_GET['start'], 'AND', 'date <=',     $_GET['end'], 'OR')
							->where('date_end >=', $_GET['start'], 'AND', 'date_end <=', $_GET['end'], 'OR')
							->where('date <',      $_GET['start'], 'AND', 'date_end >',  $_GET['end'], ') AND')
							->where('e.published', TRUE)
							->where('t.type_id', $types)
							->get() as $event)
		{
			$event['title']     = utf8_html_entity_decode($event['title']);
			$event['url_title'] = url_title($event['title']);
			$event['url']       = url('events/'.$event['id'].'/'.$event['url_title']);
			$event['color']     = color2hex($event['color']);

			$events[] = $event;
		}

		return $events;
	}

	public function _event($event_id, $title, $type_id, $date, $date_end, $description, $private_description, $location, $image_id, $published, $type)
	{
		$types = $this->model('types')->get_types();

		return $this->view('event_mini', [
			'event_id'    => $event_id,
			'title'       => $title,
			'date'        => $date,
			'date_end'    => $date_end,
			'description' => $description,
			'type'        => $types[$type_id]
		]);
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/events/controllers/ajax.php
*/