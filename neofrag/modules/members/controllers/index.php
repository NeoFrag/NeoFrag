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

class m_members_c_index extends Controller_Module
{
	public function index($members)
	{
		$this	->title($this('member_list'))
				->table
				->add_columns([
					[
						'content' => function($data){
							return NeoFrag::loader()->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']);
						},
						'size'    => TRUE
					],
					[
						'title'   => 'Membre',
						'content' => function($data, $loader){
							return '<div>'.NeoFrag::loader()->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$loader->lang($data['admin'] ? 'admin' : 'member').' '.$loader->lang($data['online'] ? 'online' : 'offline').'</small>';
						},
						'search'  => function($data){
							return $data['username'];
						}
					]/*,
					array(
						//TODO link compose
						'content' => '<?php echo $this->user() ? \'<a href="'.url('user/compose.html').'">'.icon('fa-envelope-o').' Contacter</a>\' : \'\' ?>',
						'size'    => TRUE,
						'align'   => 'right'
					)*/
				])
				->data($members)
				->no_data($this('no_members'));
			
		return new Panel([
			'title'   => $this('member_list'),
			'icon'    => 'fa-users',
			'content' => $this->table->display()
		]);
	}

	public function _member($user_id, $username)
	{
		$this->title($username);
		
		return [
			new Panel([
				'title'   => $username,
				'icon'    => 'fa-user',
				'content' => $this->load->view('profile', $this->model()->get_member_profile($user_id)),
			]),
			new Button_back('members.html')
		];
	}
	
	public function _group($title, $members)
	{
		$output = [$this->index($members)];
		
		array_unshift($output, new Panel([
			'content' => '<h2 class="no-margin">'.$this('group').' <small>'.$title.'</small>'.button('members.html', 'fa-close', $this('show_all_members'), 'danger pull-right').'</h2>'
		]));

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/members/controllers/index.php
*/