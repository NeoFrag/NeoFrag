<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events(), $page)];
	}

	public function standards($page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events('filter', 'standards'), $page)];
	}

	public function matches($page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events('filter', 'matches'), $page)];
	}

	public function upcoming($page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events('filter', 'upcoming'), $page)];
	}

	public function _type($type_id, $title, $page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events('type', $type_id), $page)];
	}

	public function _team($team_id, $title, $page = '')
	{
		return [$this->module->pagination->fix_items_per_page($this->config->events_per_page)->get_data($this->model()->get_events('team', $team_id), $page)];
	}

	public function _event($event_id, $title)
	{
		if ($event = $this->model()->check_event($event_id, $title))
		{
			return $event;
		}
	}

	public function _participant_add($event_id, $title, $current_status)
	{
		$status = $this->model('participants')->status();

		if (isset($status[$current_status]) && $this->model()->check_event($event_id, $title) && !$this->db->from('nf_events_participants')->where('user_id', $this->user->id)->where('event_id', $event_id)->empty())
		{
			return [$event_id, $title, $current_status];
		}
	}

	public function _participant_delete($event_id, $title, $user_id)
	{
		if (!$this->user->admin)
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($this->model()->check_event($event_id, $title) && !$this->db->from('nf_events_participants')->where('user_id', $user_id)->where('event_id', $event_id)->empty())
		{
			return [$event_id, $user_id];
		}
	}
}
