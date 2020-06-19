<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function index()
	{
		$types  = array_keys($this->model('types')->get_types());
		$events = [];

		foreach ($this->db	->select('e.event_id as id', 'e.title', 't.color', 't.icon', 'e.date as start', 'e.date_end as end')
							->from('nf_events e')
							->join('nf_events_types t', 'e.type_id = t.type_id')
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
			$event['color']     = get_colors($event['color']);

			$events[] = $event;
		}

		return $this->json($events);
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
