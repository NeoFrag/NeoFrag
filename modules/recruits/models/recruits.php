<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Recruits\Models;

use NF\NeoFrag\Loadables\Model;

class Recruits extends Model
{
	public function get_recruits()
	{
		$this->db	->select('r.*', 'u.id as user_id', 'u.username', 'up.avatar', 'up.sex', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined', 'tl.title as team_name')
					->from('nf_recruits r')
					->join('nf_user u',                  'r.user_id     = u.id')
					->join('nf_user_profile up',         'up.id         = u.id')
					->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
					->join('nf_teams_lang tl',           'r.team_id     = tl.team_id')
					->group_by('r.recruit_id')
					->order_by('r.date DESC');

		if (!$this->url->admin)
		{
			$this->db->where('r.closed', FALSE);
		}

		return $this->db->get();
	}

	public function check_recruit($recruit_id, $title)
	{
		$this->db	->select('r.*', 'u.id as user_id', 'u.username', 'up.avatar', 'up.sex', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined', 'tl.title as team_name')
					->from('nf_recruits r')
					->join('nf_user u',                  'r.user_id     = u.id')
					->join('nf_user_profile up',         'up.id         = u.id')
					->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
					->join('nf_teams_lang tl',           'r.team_id     = tl.team_id')
					->group_by('r.recruit_id')
					->where('r.recruit_id', $recruit_id);

		if (!$this->url->admin)
		{
			$this->db->where('r.closed', FALSE);
		}

		$recruit = $this->db->row();

		if ($recruit && url_title($recruit['title']) == $title)
		{
			return $recruit;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_recruits($title, $introduction, $description, $requierments, $size, $role, $icon, $date_end, $closed, $team_id, $image_id)
	{
		$recruit_id = $this->db->insert('nf_recruits', [
			'title'        => $title,
			'introduction' => $introduction,
			'description'  => $description,
			'requierments' => $requierments,
			'user_id'      => $this->user->id,
			'size'         => $size,
			'role'         => $role,
			'icon'         => $icon,
			'date_end'     => $date_end ?: NULL,
			'closed'       => $closed,
			'team_id'      => $team_id ?: NULL,
			'image_id'     => $image_id ?: NULL
		]);

		$this->access->init('recruits', 'recruit', $recruit_id);
	}

	public function get_teams_list()
	{
		$list = [];

		foreach ($this->db	->select('t.team_id', 't.name', 'tl.title')
							->from('nf_teams t')
							->join('nf_teams_lang tl', 't.team_id = tl.team_id')
							->where('tl.lang', $this->config->lang->info()->name)
							->order_by('tl.title')
							->get() as $team)
		{
			$list[$team['team_id']] = $team['title'];
		}

		natsort($list);

		return $list;
	}

	public function edit_recruits($recruit_id, $title, $introduction, $description, $requierments, $size, $role, $icon, $date_end, $closed, $team_id, $image_id)
	{
		$this->db	->where('recruit_id', $recruit_id)
					->update('nf_recruits', [
						'title' => $title,
						'introduction' => $introduction,
						'description'  => $description,
						'requierments' => $requierments,
						'size'         => $size,
						'role'         => $role,
						'icon'         => $icon,
						'date_end'     => $date_end ?: NULL,
						'closed'       => $closed,
						'team_id'      => $team_id ?: NULL,
						'image_id'     => $image_id ?: NULL
					]);
	}

	public function delete_recruit($recruit_id)
	{
		NeoFrag()->model2('file', $this->db->select('image_id')->from('nf_recruit')->where('recruit_id', $recruit_id)->row())->delete();

		$this->db	->where('recruit_id', $recruit_id)
					->delete('nf_recruits');
	}

	public function get_candidacies($recruit_id = '', $status = '')
	{
		$this->db	->select('rc.*', 'u.id as user_id', 'u.username', 'up.avatar', 'up.sex', 'r.title')
					->from('nf_recruits_candidacies rc')
					->join('nf_recruits r',      'rc.recruit_id = r.recruit_id')
					->join('nf_user u',          'rc.user_id    = u.id')
					->join('nf_user_profile up', 'up.id         = u.id')
					->order_by('rc.date DESC');

		if ($recruit_id)
		{
			$this->db->where('rc.recruit_id', $recruit_id);
		}

		if ($status)
		{
			$this->db->where('rc.status', $status);
		}

		return $this->db->get();
	}

	public function send_candidacy($recruit_id, $user_id, $pseudo, $email, $date_of_birth, $presentation, $motivations, $experiences)
	{
		$candidacy_id = $this->db->insert('nf_recruits_candidacies', [
			'recruit_id' => $recruit_id,
			'user_id' => $user_id,
			'pseudo' => $pseudo,
			'email' => $email,
			'date_of_birth' => $date_of_birth,
			'presentation' => $presentation,
			'motivations' => $motivations,
			'experiences' => $experiences
		]);

		return $candidacy_id;
	}

	public function check_candidacy($candidacy_id, $title)
	{
		$candidacy = $this->db	->select('rc.*', 'r.recruit_id', 'r.title', 'r.icon', 'r.role', 'r.team_id', 'tl.title as team_name', 'u.username', 'up.avatar', 'up.sex')
								->from('nf_recruits_candidacies rc')
								->join('nf_recruits r',        'rc.recruit_id = r.recruit_id')
								->join('nf_teams_lang tl',     'r.team_id     = tl.team_id')
								->join('nf_user u',            'rc.user_id    = u.id')
								->join('nf_user_profile up',   'up.id         = u.id')
								->where('rc.candidacy_id', $candidacy_id)
								->row();

		if ($candidacy && url_title($candidacy['title']) == $title)
		{
			return $candidacy;
		}

		return FALSE;
	}

	public function update_candidacy($candidacy_id, $reply, $status)
	{
		$this->db	->where('candidacy_id', $candidacy_id)
					->update('nf_recruits_candidacies', [
						'reply'  => $reply,
						'status' => $status
					]);
	}

	public function delete_candidacy($candidacy_id)
	{
		$this->db	->where('candidacy_id', $candidacy_id)
					->delete('nf_recruits_candidacies');
	}

	public function postulated($user_id, $recruit_id, $title)
	{
		$candidacy = $this->db	->select('rc.candidacy_id', 'rc.recruit_id', 'rc.date', 'rc.user_id', 'rc.status', 'r.title')
								->from('nf_recruits_candidacies rc')
								->join('nf_recruits r', 'rc.recruit_id = r.recruit_id')
								->where('rc.user_id', $user_id)
								->where('rc.recruit_id', $recruit_id)
								->where('r.title', $title);

		$candidacy = $this->db->row();

		if ($candidacy && $candidacy['title'] == $title)
		{
			return $candidacy;
		}

		return FALSE;
	}

	public function get_votes($candidacy_id)
	{
		return $this->db->select('rcv.*', 'u.username', 'up.avatar', 'up.sex')
						->from('nf_recruits_candidacies_votes rcv')
						->join('nf_user u',            'u.id   = rcv.user_id')
						->join('nf_user_profile up',   'up.id  = u.id')
						->where('rcv.candidacy_id', $candidacy_id)
						->get();
	}

	public function send_vote($candidacy_id, $vote, $comment)
	{
		$this->db->insert('nf_recruits_candidacies_votes', [
			'candidacy_id' => $candidacy_id,
			'user_id'      => $this->user->id,
			'vote'         => $vote,
			'comment'      => $comment
		]);
	}

	public function update_vote($user_id, $candidacy_id, $vote, $comment)
	{
		$this->db	->where('candidacy_id', $candidacy_id)
					->where('user_id', $user_id)
					->update('nf_recruits_candidacies_votes', [
						'vote'    => $vote,
						'comment' => $comment
					]);
	}

	public function check_team($team_id, $name)
	{
		return $this->db->select('t.team_id', 't.name', 'tl.title', 't.image_id', 't.icon_id', 'tl.description', 't.game_id', 'gl.title as game', 'g.icon_id as game_icon')
						->from('nf_teams t')
						->join('nf_teams_lang tl', 't.team_id = tl.team_id')
						->join('nf_games g',       'g.game_id = t.game_id')
						->join('nf_games_lang gl', 'g.game_id = gl.game_id')
						->where('t.team_id', $team_id)
						->where('t.name', $name)
						->where('tl.lang', $this->config->lang->info()->name)
						->row();
	}

	public function check_role($title)
	{
		$role = $this->db	->select('role_id', 'title')
							->from('nf_teams_roles')
							->where('title', $title)
							->row();

		if ($role && $title == $role['title'])
		{
			return $role;
		}

		return FALSE;
	}
}
