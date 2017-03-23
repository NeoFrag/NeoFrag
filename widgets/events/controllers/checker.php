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

/*
NeoFrag Alpha 0.1.6
./widgets/events/controllers/checker.php
*/