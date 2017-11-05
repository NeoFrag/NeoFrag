<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_events_c_checker extends Controller
{
	public function events($settings = [])
	{
		if (in_array($settings['type_id'], array_map(function($a){
			return $a['type_id'];
		}, $this->model('types')->get_types())))
		{
			return [
				'type_id' => $settings['type_id']
			];
		}
	}

	public function event($settings = [])
	{
		if (in_array($settings['event_id'], array_map(function($a){
			return $a['event_id'];
		}, $this->model()->get_events())))
		{
			return [
				'event_id' => $settings['event_id']
			];
		}
	}
}
