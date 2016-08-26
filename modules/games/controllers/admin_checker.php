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

class m_games_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model('maps')->get_maps(), $page)];
	}

	public function _edit($game_id, $name, $page = '')
	{
		if ($game = $this->model()->check_game($game_id, $name, 'default'))
		{
			return array_merge($game, [$this->pagination->get_data($this->model('maps')->get_maps($game_id), $page)]);
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function delete($game_id, $name)
	{
		$this->ajax();

		if ($game = $this->model()->check_game($game_id, $name))
		{
			return [$game['game_id'], $game['title']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _maps_add($game_id = NULL, $title = NULL)
	{
		if ($game_id === NULL && $title === NULL)
		{
			return [];
		}
		
		if ($game = $this->model()->check_game($game_id, $title))
		{
			return [$game_id, $game['name']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _maps_edit($map_id, $title)
	{
		if ($map = $this->model('maps')->check_map($map_id, $title))
		{
			return $map;
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _maps_delete($map_id, $title)
	{
		$this->ajax();

		if ($map = $this->model('maps')->check_map($map_id, $title))
		{
			return [$map_id, $map['title']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _modes_add($game_id, $title)
	{
		if ($game = $this->model()->check_game($game_id, $title))
		{
			return [$game_id, $game['name']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _modes_edit($mode_id, $title)
	{
		if ($mode = $this->model('modes')->check_mode($mode_id, $title))
		{
			return $mode;
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _modes_delete($mode_id, $title)
	{
		$this->ajax();

		if ($mode = $this->model('modes')->check_mode($mode_id, $title))
		{
			return [$mode_id, $mode['title']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/games/controllers/admin_checker.php
*/