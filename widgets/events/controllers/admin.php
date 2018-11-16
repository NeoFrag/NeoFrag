<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Events\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Admin extends Controller
{
	public function events($settings = [])
	{
		return $this->view('admin_events', [
			'type_id' => isset($settings['type_id']) ? $settings['type_id'] : 0,
			'types'   => $this->module('events')->model('types')->get_types()
		]);
	}

	public function event($settings = [])
	{
		return $this->view('admin_event', [
			'event_id' => isset($settings['event_id']) ? $settings['event_id'] : 0,
			'events'   => $this->model()->get_events()
		]);
	}
}
