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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_games_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return array(array());
		//return array($this->load->library('pagination')->get_data($this->model('maps')->get_maps(), $page));
	}

	public function _edit($game_id, $name, $tab = 'default')
	{
		if ($game = $this->model()->check_game($game_id, $name, $tab))
		{
			return $game + array($tab);
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function delete($game_id, $name)
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$this->ajax();
		}

		if ($game = $this->model()->check_game($game_id, $name))
		{
			return array($game['game_id'], $game['title']);
		}
		else if ($this->config->ajax_url)
		{
			return '<h4 class="alert-heading">Erreur</h4>Cette équipe a déjà été supprimée.';
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1
./modules/games/controllers/admin_checker.php
*/