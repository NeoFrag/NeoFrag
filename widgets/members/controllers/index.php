<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Members\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		$members = $this->db->select('user_id', 'username', 'registration_date')
							->from('nf_users')
							->where('deleted', FALSE)
							->order_by('registration_date DESC')
							->limit(5)
							->get();

		if (!empty($members))
		{
			return $this->panel()
						->heading($this->lang('Derniers membres'))
						->body($this->view('index', [
							'members'  => $members
						]), FALSE)
						->footer('<a href="'.url('members').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('Liste des membres').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Derniers membres'))
						->body($this->lang('Aucun membre pour le moment'));
		}
	}

	public function online($config = [])
	{
		$admins = $members = [];
		$nb_admins = $nb_members = 0;

		foreach ($this->db	->select('u.user_id', 'u.username', 'u.admin', 'up.avatar', 'up.sex', 'MAX(s.last_activity) AS last_activity')
							->from('nf_sessions s')
							->join('nf_users u', 'u.user_id = s.user_id AND u.deleted = "0"', 'INNER')
							->join('nf_users_profiles up', 'u.user_id = up.user_id')
							->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')
							->where('s.is_crawler', FALSE)
							->group_by('u.user_id')
							->order_by('u.username')
							->get() as $user)
		{
			if ($user['admin'])
			{
				$admins[] = $user;
				$nb_admins++;
			}
			else
			{
				$members[] = $user;
				$nb_members++;
			}
		}

		$output = [
			$this	->panel()
					->heading($this->lang('Qui est en ligne ?'))
					->body($this->view('online', [
						'administrators' => $admins,
						'members'        => $members,
						'nb_admins'      => $nb_admins,
						'nb_members'     => $nb_members,
						'nb_visitors'    => $this->session->current_sessions() - $nb_admins - $nb_members
					]))
		];

		if ($nb_admins)
		{
			$output[] = $this->view('online_modal', [
				'name'  => 'administrators',
				'title' => $this->lang('Administrateurs en ligne'),
				'users' => $admins
			]);
		}

		if ($nb_members)
		{
			$output[] = $this->view('online_modal', [
				'name'  => 'members',
				'title' => $this->lang('Membres en ligne'),
				'users' => $members
			]);
		}

		return $output;
	}

	public function online_mini($config = [])
	{
		return $this->view('online_mini', [
			'members' => $this->session->current_sessions(),
			'align'   => !empty($config['align']) ? $config['align'] : 'pull-right'
		]);
	}
}
