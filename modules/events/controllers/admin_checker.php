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

class m_events_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_events(), $page)];
	}

	public function standards($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_events('filter', 'standards'), $page)];
	}

	public function matches($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_events('filter', 'matches'), $page)];
	}

	public function upcoming($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_events('filter', 'upcoming'), $page)];
	}

	public function _event($event_id, $title)
	{
		if ($event = $this->model()->check_event($event_id, $title))
		{
			return $event;
		}
	}

	public function _add()
	{
		if (!$this->is_authorized('add_event'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		return [];
	}

	public function _edit($event_id, $title)
	{
		if (!$this->is_authorized('modify_event'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
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
			throw new Exception(NeoFrag::UNAUTHORIZED);
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
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		return [];
	}

	public function _types_edit($type_id, $name)
	{
		if (!$this->is_authorized('modify_events_type'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
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
			throw new Exception(NeoFrag::UNAUTHORIZED);
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

/*
NeoFrag Alpha 0.1.6
./modules/events/controllers/admin_checker.php
*/