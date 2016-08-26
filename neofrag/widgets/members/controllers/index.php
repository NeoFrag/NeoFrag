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

class w_members_c_index extends Controller_Widget
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
			return new Panel([
				'title'        => $this('last_members'),
				'content'      => $this->load->view('index', [
					'members'  => $members
				]),
				'body'         => FALSE,
				'footer'       => '<a href="'.url('members.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('members_list').'</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('last_members'),
				'content' => $this('no_members')
			]);
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

		$output = [new Panel([
			'title'   => $this('whos_online'),
			'content' => $this->load->view('online', [
				'administrators' => $admins,
				'members'        => $members,
				'nb_admins'      => $nb_admins,
				'nb_members'     => $nb_members,
				'nb_visitors'    => $this->session->current_sessions() - $nb_admins - $nb_members
			])
		])];
		
		if ($nb_admins)
		{
			$output[] = $this->load->view('online_modal', [
				'name'  => 'administrators',
				'title' => $this('admins_online'),
				'users' => $admins
			]);
		}
		
		if ($nb_members)
		{
			$output[] = $this->load->view('online_modal', [
				'name'  => 'members',
				'title' => $this('members_online'),
				'users' => $members
			]);
		}
		
		return $output;
	}
	
	public function online_mini($config = [])
	{
		return $this->load->view('online_mini', [
			'members' => $this->session->current_sessions(),
			'align'   => !empty($config['align']) ? $config['align'] : 'pull-right'
		]);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/widgets/members/controllers/index.php
*/