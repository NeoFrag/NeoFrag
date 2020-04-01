<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events\Models;

use NF\NeoFrag\Loadables\Model;

class Matches extends Model
{
	public function get_match_info($event_id)
	{
		static $result = [];

		if (!isset($result[$event_id]))
		{
			if ($match = $this->db->from('nf_events_matches')->where('event_id', $event_id)->row())
			{
				$result[$event_id] = array_merge($match, [
					'opponent' => $this->db->from('nf_events_matches_opponents')->where('opponent_id', $match['opponent_id'])->row(),
					'scores'   => $this->get_global_scores($event_id),
					'game'     => $this->db	->select('g.game_id', 'COALESCE(g.icon_id, g2.icon_id) as icon_id', 'gl.title', 'g.name')
											->from('nf_teams t')
											->join('nf_games g',       'g.game_id = t.game_id')
											->join('nf_games g2',      'g.parent_id = g2.game_id')
											->join('nf_games_lang gl', 'g.game_id = gl.game_id')
											->join('nf_games_lang gl2', 'g2.game_id = gl2.game_id')
											->where('t.team_id', $match['team_id'])
											->row(),
					'team'     => $this->db	->select('t.team_id', 't.name', 'COALESCE(t.icon_id, g.icon_id) as icon_id', 'tl.title')
											->from('nf_teams t')
											->join('nf_teams_lang tl', 'tl.team_id = t.team_id')
											->join('nf_games g',       'g.game_id  = t.game_id')
											->where('t.team_id', $match['team_id'])
											->row()
				]);
			}
			else
			{
				$result[$event_id] = FALSE;
			}
		}

		return $result[$event_id];
	}

	public function get_opponents_list()
	{
		$opponents = [];

		foreach ($this->db->select('opponent_id', 'title')->from('nf_events_matches_opponents')->get() as $opponent)
		{
			$opponents[$opponent['opponent_id']] = $opponent['title'];
		}

		array_natsort($opponents);

		return $opponents;
	}

	public function get_global_scores($event_id)
	{
		$scores = [];

		if ($rounds = $this->db->select('score1', 'score2')->from('nf_events_matches_rounds')->where('event_id', $event_id)->get())
		{
			if (count($rounds) == 1)
			{
				$scores = array_values($rounds[0]);
			}
			else
			{
				$scores = [0, 0];

				foreach ($rounds as $round)
				{
					if ($round['score1'] != $round['score2'])
					{
						$scores[(int)($round['score1'] < $round['score2'])]++;
					}
				}
			}
		}

		return $scores;
	}

	public function label_scores($score1, $score2)
	{
		$class = 'default';

		if ($score1 > $score2)
		{
			$class = 'success';
		}
		else if ($score1 < $score2)
		{
			$class = 'danger';
		}
		else
		{
			$class = 'primary';
		}

		return '<span class="badge badge-'.$class.'">'.$score1.' - '.$score2.'</span>';
	}

	public function label_global_scores($event_id)
	{
		return call_user_func_array([$this, 'label_scores'], $this->get_global_scores($event_id));
	}

	public function display_scores($scores, &$color, $opponents = FALSE)
	{
		if (!$scores)
		{
			return '';
		}

		if ($opponents)
		{
			$scores = array_reverse($scores);
		}

		if ($scores[0] > $scores[1])
		{
			$color = 'text-success';
			$icon  = 'fas fa-angle-up';
		}
		else if ($scores[0] < $scores[1])
		{
			$color = 'text-danger';
			$icon  = 'fas fa-angle-down';
		}
		else
		{
			$color = 'text-primary';

			if ($opponents)
			{
				$icon  = 'fas fa-angle-left';
			}
			else
			{
				$icon  = 'fas fa-angle-right';
			}
		}

		return icon($icon.' '.$color);
	}
}
