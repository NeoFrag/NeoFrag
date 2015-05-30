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

class w_members_c_index extends Controller_Widget
{
	public function index($config = array())
	{
		$members = $this->db->select('user_id', 'username', 'registration_date')
							->from('nf_users')
							->where('deleted', FALSE)
							->order_by('registration_date DESC')
							->limit(5)
							->get();
		
		if (!empty($members))
		{
			return new Panel(array(
				'title'        => 'Derniers membres',
				'content'      => $this->load->view('index', array(
					'members'  => $members
				)),
				'body'         => FALSE,
				'footer'       => '<a href="{base_url}members.html">{fa-icon arrow-circle-o-right} Liste des membres</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Derniers membres',
				'content' => 'Aucun membre pour le moment'
			));
		}
	}
	
	public function online($config = array())
	{
		$users = $this->db	->select('u.user_id', 'u.username', 'u.admin', 'up.avatar', 'up.sex', 'MAX(s.last_activity) AS last_activity')
							->from('nf_sessions s')
							->join('nf_users u', 'u.user_id = s.user_id')
							->join('nf_users_profiles up', 'u.user_id = up.user_id')
							->where('s.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')
							->order_by('u.username')
							->get();

		$output = array(new Panel(array(
			'title'   => 'Qui est en ligne ?',
			'content' => $this->load->view('online', array(
				'administrators'    => ($admins = array_filter($users, function($a){
					return $a['admin'];
				})),
				'members'           => ($members = array_filter($users, function($a){
					return $a['user_id'] && !$a['admin'];
				})),
				'nb_administrators' => ($nb_administrators = count($admins)),
				'nb_members'        => ($nb_members = count($members)),
				'nb_visitors'       => count(array_filter($users, function($a){
					return !$a['user_id'];
				}))
			))
		)));
		
		if ($nb_administrators)
		{
			$output[] = $this->load->view('online-modal', array(
				'name'  => 'administrators',
				'title' => 'Administrateurs en ligne',
				'users' => $admins
			));
		}
		
		if ($nb_administrators)
		{
			$output[] = $this->load->view('online-modal', array(
				'name'  => 'members',
				'title' => 'Membres en ligne',
				'users' => $members
			));
		}
		
		return $output;
	}
	
	public function online_mini($config = array())
	{
		return $this->load->view('online-mini', array(
			'members' => $this->session->current_sessions()
		));
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/widgets/members/controllers/index.php
*/