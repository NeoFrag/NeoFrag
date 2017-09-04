<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Recruits\Models;

use NF\NeoFrag\Loadables\Model;

class Recruits extends Model
{
	public function get_last_recruits()
	{
		return $this->db->select('r.*', 'u.user_id', 'u.username', 'up.avatar', 'up.sex', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined', 'tl.title as team_name')
						->from('nf_recruits r')
						->join('nf_users u',                 'r.user_id     = u.user_id')
						->join('nf_users_profiles up',       'up.user_id    = u.user_id')
						->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
						->join('nf_teams_lang tl',           'r.team_id     = tl.team_id')
						->group_by('r.recruit_id')
						->order_by('r.date DESC')
						->limit(5)
						->get();
	}

	public function get_recruits()
	{
		return $this->db->select('r.*', 'u.user_id', 'u.username', 'up.avatar', 'up.sex', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined', 'tl.title as team_name')
						->from('nf_recruits r')
						->join('nf_users u',                 'r.user_id     = u.user_id')
						->join('nf_users_profiles up',       'up.user_id    = u.user_id')
						->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
						->join('nf_teams_lang tl',           'r.team_id     = tl.team_id')
						->group_by('r.recruit_id')
						->order_by('r.date DESC')
						->get();
	}

	public function get_recruit($recruit_id)
	{
		return $this->db->select('r.*', 'COUNT(DISTINCT rc.candidacy_id) as candidacies', 'COUNT(DISTINCT CASE WHEN rc.status = \'1\' THEN rc.candidacy_id END) as candidacies_pending', 'COUNT(DISTINCT CASE WHEN rc.status = \'2\' THEN rc.candidacy_id END) as candidacies_accepted', 'COUNT(DISTINCT CASE WHEN rc.status = \'3\' THEN rc.candidacy_id END) as candidacies_declined', 'tl.title as team_name')
						->from('nf_recruits r')
						->join('nf_recruits_candidacies rc', 'rc.recruit_id = r.recruit_id')
						->join('nf_teams_lang tl',           'r.team_id     = tl.team_id')
						->group_by('r.recruit_id')
						->where('r.recruit_id', $recruit_id)
						->row();
	}
}
