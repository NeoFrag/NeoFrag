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

class m_games_m_modes extends Model
{
	public function get_modes($game_id = NULL)
	{
		if ($game_id)
		{
			$this->db->where('game_id', $game_id);
		}
		
		return $this->db->from('nf_games_modes')
						->order_by('title')
						->get();
	}

	public function get_modes_list()
	{
		$modes = [];

		foreach ($this->db	->select('m.mode_id', 'm.title', 'gl.title as game_title', 'gl2.title as game_title2')
							->from('nf_games_modes m')
							->join('nf_games g',        'm.game_id = g.game_id')
							->join('nf_games_lang gl',  'm.game_id = gl.game_id')
							->join('nf_games_lang gl2', 'g.parent_id = gl2.game_id')
							->order_by('If(g.parent_id IS NULL, gl.title, CONCAT(gl2.title, gl.title))', 'm.title')
							->get() as $mode)
		{
			$modes[$mode['mode_id']] = $mode['title'].' ('.implode(' - ', array_filter([$mode['game_title2'], $mode['game_title']])).')';
		}

		return $modes;
	}

	public function check_mode($mode_id, $title)
	{
		$mode = $this->db->from('nf_games_modes')
						->where('mode_id', $mode_id)
						->row();

		if ($mode && url_title($mode['title']) == $title)
		{
			return $mode;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_mode($game_id, $title)
	{
		return $this->db->insert('nf_games_modes', [
			'game_id' => $game_id,
			'title'   => $title
		]);
	}
	
	public function edit_mode($mode_id, $title)
	{
		$this->db	->where('mode_id', $mode_id)
					->update('nf_games_modes', [
						'title'    => $title
					]);
	}

	public function delete_mode($mode_id)
	{
		$this->db	->where('mode_id', $mode_id)
					->delete('nf_games_modes');
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/games/models/modes.php
*/