<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_events(), $page)];
	}

	public function standards($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_events('filter', 'standards'), $page)];
	}

	public function matches($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_events('filter', 'matches'), $page)];
	}

	public function upcoming($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_events('filter', 'upcoming'), $page)];
	}

	public function _event($event_id, $title)
	{
		if ($event = $this->model()->check_event($event_id, $title))
		{
			return $event;
		}
	}

	public function add()
	{
		if (!$this->is_authorized('add_event'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($event_id, $title)
	{
		if (!$this->is_authorized('modify_event'))
		{
			$this->error->unauthorized();
		}

		if ($event = $this->model()->check_event($event_id, $title))
		{
			return $event;
		}
	}

	public function delete($event_id, $title)
	{
		if (!$this->is_authorized('delete_event'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($event = $this->model()->check_event($event_id, $title))
		{
			return [$event_id, $event['title']];
		}
	}

	public function _types_add()
	{
		if (!$this->is_authorized('add_events_type'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _types_edit($type_id, $name)
	{
		if (!$this->is_authorized('modify_events_type'))
		{
			$this->error->unauthorized();
		}

		if ($type = $this->model('types')->check_type($type_id, $name))
		{
			return $type;
		}
	}

	public function _types_delete($type_id, $name)
	{
		if (!$this->is_authorized('delete_events_type'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($type = $this->model('types')->check_type($type_id, $name))
		{
			return [$type_id, $type['title']];
		}
	}

	public function _round_delete($event_id, $title, $round_id)
	{
		$this->ajax();

		if ($this->model()->check_event($event_id, $title) && $this->db->select('round_id')->from('nf_events_matches_rounds')->where('round_id', $round_id)->where('event_id', $event_id)->row())
		{
			return [$round_id];
		}
	}
}
