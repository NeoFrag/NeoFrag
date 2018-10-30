<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Games\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model('maps')->get_maps(), $page)];
	}

	public function add()
	{
		if (!$this->is_authorized('add_games'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($game_id, $name, $page = '')
	{
		if (!$this->is_authorized('modify_games'))
		{
			$this->error->unauthorized();
		}

		if ($game = $this->model()->check_game($game_id, $name, 'default'))
		{
			return array_merge($game, [$this->module->pagination->get_data($this->model('maps')->get_maps($game_id), $page)]);
		}
	}

	public function delete($game_id, $name)
	{
		if (!$this->is_authorized('delete_games'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($game = $this->model()->check_game($game_id, $name))
		{
			return [$game['game_id'], $game['title']];
		}
	}

	public function _maps_add($game_id = NULL, $title = NULL)
	{
		if (!$this->is_authorized('add_games_maps'))
		{
			$this->error->unauthorized();
		}

		if ($game_id === NULL && $title === NULL)
		{
			return [];
		}

		if ($game = $this->model()->check_game($game_id, $title))
		{
			return [$game_id, $game['name']];
		}
	}

	public function _maps_edit($map_id, $title)
	{
		if (!$this->is_authorized('modify_games_maps'))
		{
			$this->error->unauthorized();
		}

		if ($map = $this->model('maps')->check_map($map_id, $title))
		{
			return $map;
		}
	}

	public function _maps_delete($map_id, $title)
	{
		if (!$this->is_authorized('delete_games_maps'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($map = $this->model('maps')->check_map($map_id, $title))
		{
			return [$map_id, $map['title']];
		}
	}

	public function _modes_add($game_id, $title)
	{
		if ($game = $this->model()->check_game($game_id, $title))
		{
			return [$game_id, $game['name']];
		}
	}

	public function _modes_edit($mode_id, $title)
	{
		if ($mode = $this->model('modes')->check_mode($mode_id, $title))
		{
			return $mode;
		}
	}

	public function _modes_delete($mode_id, $title)
	{
		$this->ajax();

		if ($mode = $this->model('modes')->check_mode($mode_id, $title))
		{
			return [$mode_id, $mode['title']];
		}
	}
}
