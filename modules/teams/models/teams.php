<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams\Models;

use NF\NeoFrag\Loadables\Model;

class Teams extends Model
{
	public function get_teams()
	{
		return $this->db->select('t.team_id', 't.name', 'tl.title', 't.image_id', 't.icon_id', 'COUNT(DISTINCT u.user_id) as users', 't.game_id', 'g.name as game', 'gl.title as game_title', 'g.icon_id as game_icon')
						->from('nf_teams t')
						->join('nf_teams_lang tl',  't.team_id  = tl.team_id')
						->join('nf_teams_users tu', 't.team_id  = tu.team_id')
						->join('nf_users u',        'tu.user_id = u.user_id AND u.deleted = "0"')
						->join('nf_games g',        'g.game_id  = t.game_id')
						->join('nf_games_lang gl',  'g.game_id  = gl.game_id')
						->where('tl.lang', $this->config->lang)
						->where('gl.lang', $this->config->lang)
						->group_by('t.team_id')
						->order_by('t.order', 't.team_id')
						->get();
	}

	public function get_teams_list()
	{
		$teams = [];

		foreach ($this->db	->select('t.team_id', 'tl.title', 'gl.title as game_title')
							->from('nf_teams t')
							->join('nf_teams_lang tl', 't.team_id = tl.team_id')
							->join('nf_games_lang gl', 't.game_id = gl.game_id')
							->order_by('gl.title', 'tl.title')
							->get() as $team)
		{
			$teams[$team['team_id']] = $team['title'].' ('.$team['game_title'].')';
		}

		return $teams;
	}

	public function get_games_list()
	{
		$list = [];

		foreach ($this->db->select('g.game_id', 'gl.title')->from('nf_games g')->join('nf_games_lang gl', 'gl.game_id = g.game_id')->where('g.parent_id', NULL)->where('gl.lang', $this->config->lang)->get() as $game)
		{
			$list[$game['game_id']] = $game['title'];
		}

		return $list;
	}

	public function get_players($team_id)
	{
		return $this->db->select('u.user_id', 'u.username', 'u.admin', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online', 'r.title')
						->from('nf_teams_users    tu')
						->join('nf_users          u',  'tu.user_id = u.user_id AND u.deleted = "0"', 'INNER')
						->join('nf_users_profiles up', 'u.user_id  = up.user_id')
						->join('nf_teams_roles    r',  'r.role_id  = tu.role_id')
						->join('nf_sessions       s',  'u.user_id  = s.user_id')
						->where('tu.team_id', $team_id)
						->group_by('u.username')
						->order_by('r.order', 'r.role_id', 'u.username')
						->get();
	}

	public function check_team($team_id, $name)
	{
		return $this->db	->select('t.team_id', 't.name', 'tl.title', 't.image_id', 't.icon_id', 'tl.description', 't.game_id', 'gl.title as game', 'g.icon_id as game_icon')
							->from('nf_teams t')
							->join('nf_teams_lang tl', 't.team_id = tl.team_id')
							->join('nf_games g',       'g.game_id = t.game_id')
							->join('nf_games_lang gl', 'g.game_id = gl.game_id')
							->where('t.team_id', $team_id)
							->where('t.name', $name)
							->where('tl.lang', $this->config->lang)
							->row();
	}

	public function add_team($title, $game_id, $image_id, $icon_id, $description)
	{
		$team_id = $this->db->insert('nf_teams', [
								'game_id'  => $game_id,
								'image_id' => $image_id,
								'icon_id'  => $icon_id,
								'name'     => url_title($title)
							]);

		$this->db	->insert('nf_teams_lang', [
						'team_id'     => $team_id,
						'lang'        => $this->config->lang,
						'title'       => $title,
						'description' => $description
					]);

		return $team_id;
	}

	public function edit_team($team_id, $title, $game_id, $image_id, $icon_id, $description)
	{
		$this->db	->where('team_id', $team_id)
					->update('nf_teams', [
						'image_id' => $image_id,
						'icon_id'  => $icon_id,
						'game_id'  => $game_id,
						'name'     => url_title($title)
					]);

		$this->db	->where('team_id', $team_id)
					->where('lang', $this->config->lang)
					->update('nf_teams_lang', [
						'title'       => $title,
						'description' => $description
					]);
	}

	public function delete_team($team_id)
	{
		$this->file->delete($this->db->select('image_id', 'icon_id')->from('nf_teams')->where('team_id', $team_id)->row());

		$this->groups->delete('teams', $team_id);

		$this->db	->where('team_id', $team_id)
					->delete('nf_teams');
	}
}
