<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_teams_c_checker extends Controller_Module
{
	public function _team($team_id, $name)
	{
		if ($team = $this->model()->check_team($team_id, $name))
		{
			return $team;
		}
	}
}
