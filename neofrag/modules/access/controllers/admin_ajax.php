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

class m_access_c_admin_ajax extends Controller_Module
{
	public function index($action, $title, $icon, $module_name, $id)
	{
		$groups = [];
		
		foreach (array_keys($this->groups()) as $group_id)
		{
			$groups[$group_id] = NeoFrag::loader()->access($module_name, $action, $id, $group_id);
		}
		
		$ambiguous = FALSE;
		
		foreach ($this->db->select('user_id')->from('nf_users')->where('deleted', FALSE)->get() as $user_id)
		{
			if (NeoFrag::loader()->access($module_name, $action, $id, NULL, $user_id) === NULL)
			{
				$ambiguous = TRUE;
				break;
			}
		}
		
		return new Col(new Panel([
			'title'   => '<span class="pull-right"><span class="text-danger access-ambiguous"'.(!$ambiguous ? ' style="display: none;"' : '').'>'.icon('fa-warning').' '.$this('ambiguities_to_correct').'</span>&nbsp;&nbsp;&nbsp;'.button('#', 'fa-users', $this('users'), 'info access-users').'</span>'.$title,
			'icon'    => $icon,
			'content' => $this->load->view('details', [
				'groups' => $groups
			]),
			'body'    => FALSE
		]), 'col-md-12 col-lg-7');
	}
	
	public function update($module_name, $action, $id, $groups, $user, $title, $icon)
	{
		$output = [];
		
		if ($groups)
		{
			$count      = array_count_values($groups);
			$authorized = isset($count[0]) ? $count[0] >= $count[1] : 0;
			
			$this->model()	->delete($module_name, $action, $id, 'group')
							->add($module_name, $action, $id, 'group', array_keys(array_filter($groups, function($a) use ($authorized){
								return $a == $authorized;
							})), $authorized);
		}
		else if ($user)
		{
			$this->model()->delete($module_name, $action, $id, 'user', $user_id = array_keys($user)[0]);
			
			if (($authorized = current($user)) != -1)
			{
				$this->model()->add($module_name, $action, $id, 'user', $user_id, $authorized);
			}
		}
		
		$this->access->reload();
		
		if ($groups)
		{
			$output['details'] = display($this->index($action, $title, $icon, $module_name, $id));
		}
		else if ($user)
		{
			$output['user_authorized'] = $authorized = $this->access($module_name, $action, $id, NULL, $user_id);
			$output['user_ambiguous']  = $authorized === NULL;
			$output['user_forced']     = is_int($authorized);
		}
		
		$output['count']     = $this->access->count($module_name, $action, $id, $ambiguous);
		$output['ambiguous'] = $ambiguous;
		
		return $output;
	}
	
	public function users($action, $title, $icon, $module_name, $id)
	{
		$this	->table
				->add_columns([
						[
						'title'   => $this('member'),
						'content' => function($data){
							return NeoFrag::loader()->user->link($data['user_id'], $data['username']).'<span data-user-id="'.$data['user_id'].'"></span>';
						},
						'sort'    => function($data){
							return $data['username'];
						},
						'search'  => function($data){
							return $data['username'];
						}
					],
					[
						'title'   => $this('groups'),
						'content' => function($data){
							return NeoFrag::loader()->groups->user_groups($data['user_id']);
						},
						'sort'    => function($data){
							return NeoFrag::loader()->groups->user_groups($data['user_id'], FALSE);
						},
						'search'  => function($data){
							return NeoFrag::loader()->groups->user_groups($data['user_id'], FALSE);
						}
					],
					[
						'content' => function($data, $loader){
							$output = '';
							
							if ($data['active'] === NULL)
							{
								$output = '<div data-toggle="tooltip" title="'.$loader->lang('ambiguity').'">'.icon('fa-warning text-danger').'</div>';
							}
							else if (is_int($data['active']))
							{
								$output = '<a class="access-revoke" href="#" data-toggle="tooltip" title="'.$loader->lang('reset_automatic').'">'.icon('fa-thumb-tack').'</a>';
							}
							
							return '<td class="access-status">'.$output.'</td>';
						},
						'sort'    => function($data){
							return $data['active'] === NULL;
						},
						'size'    => TRUE,
						'td'      => FALSE
					],
					[
						'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$this('authorized_member').'">'.icon('fa-check').'</i></div>',
						'content' => function($data, $loader){
							return $loader->view('radio', [
								'class'  => 'success',
								'active' => $data['active']
							]);
						},
						'td'      => FALSE
					],
					[
						'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$this('forbidden_member').'">'.icon('fa-ban').'</i></div>',
						'content' => function($data, $loader){
							static $admins;
							
							if ($admins === NULL)
							{
								$admins = NeoFrag::loader()->groups()['admins']['users'];
							}
							
							return in_array($data['user_id'], $admins) ? '<td></td>' : $loader->view('radio', [
								'class'  => 'danger',
								'active' => !$data['active'] && $data['active'] !== NULL
							]);
						},
						'td'      => FALSE
					]
				])
				->data($this->db->select('user_id', 'username')->from('nf_users')->where('deleted', FALSE)->get())
				->preprocessing(function($row) use ($module_name, $action, $id){
					$row['active'] = NeoFrag::loader()->access($module_name, $action, $id, NULL, $row['user_id']);
					return $row;
				})
				->sort_by(3, SORT_DESC)
				->sort_by(2, SORT_ASC)
				->sort_by(1, SORT_ASC);

		return $this->load->view('users', [
			'title' => $title,
			'icon'  => $icon,
			'users' => $this->table->display()
		]);
	}
	
	public function reset($module, $type, $id)
	{
		$this->access	->delete($module, $id)
						->init($module, $type, $id);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/access/controllers/admin_ajax.php
*/