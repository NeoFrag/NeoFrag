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

class m_members_c_admin extends Controller_Module
{
	public $administrable = FALSE;

	public function index($members)
	{
		$table_groups = $this
			->table
			->add_columns([
				[
					'content' => function($data){
						return NeoFrag::loader()->groups->display($data['data_id']);
					},
					'search'  => function($data){
						return NeoFrag::loader()->groups->display($data['data_id'], FALSE, FALSE);
					},
					'sort'    => function($data){
						return NeoFrag::loader()->groups->display($data['data_id'], FALSE, FALSE);
					}
				],
				[
					'content' => [
						function($data){
							return button_edit('admin/members/groups/edit/'.$data['url'].'.html');
						},
						function($data){
							if (!$data['auto'])
							{
								return button_delete('admin/members/groups/delete/'.$data['url'].'.html');
							}
						}
					]
				]
			])
			->data($this->groups())
			->pagination(FALSE)
			->save();

		$table_users = $this
			->table
			->add_columns([
				[
					'title'   => $this('member'),
					'content' => function($data){
						return NeoFrag::loader()->user->link($data['user_id'], $data['username']);
					},
					'sort'    => function($data){
						return $data['username'];
					},
					'search'  => function($data){
						return $data['username'];
					}
				],
				[
					'title'   => $this('email'),
					'content' => function($data){
						return '<a href="mailto:'.$data['email'].'">'.$data['email'].'</a>';
					},
					'sort'    => function($data){
						return $data['email'];
					},
					'search'  => function($data){
						return $data['email'];
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
					'title'   => $this('registration_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['registration_date'];
					}
				],
				[
					'title'   => $this('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity_date']).'">'.time_span($data['last_activity_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['last_activity_date'];
					}
				],
				[
					'content' => [
						function($data, $loader){
							return button('admin/members/ban/'.$data['user_id'].'/'.url_title($data['username']).'.html', 'fa-ban', $loader->lang('ban'), 'warning');
						},
						function($data){
							return button_edit('admin/members/'.$data['user_id'].'/'.url_title($data['username']).'.html');
						},
						function($data){
							return button_delete('admin/members/delete/'.$data['user_id'].'/'.url_title($data['username']).'.html');
						}
					]
				]
			])
			->data($members)
			->save();
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('groups'),
					'icon'    => 'fa-users',
					'content' => $table_groups->display(),
					'footer'  => button_add('admin/members/groups/add.html', $this('add_group')),
					'size'    => 'col-md-12 col-lg-3'
				])
			),
			new Col(
				new Panel([
					'title'   => $this('members'),
					'icon'    => 'fa-users',
					'content' => $table_users->display(),
					'size'    => 'col-md-12 col-lg-9'
				])
			)
		);
	}

	public function _edit($member_id, $username, $email, $groups, $first_name, $last_name, $avatar, $signature, $date_of_birth, $sex, $location, $website, $quote)
	{
		$form_member = $this
			->title($this('edit_member'))
			->subtitle($username)
			->css('groups')
			->form
			->add_rules('members', [
				'username'      => $username,
				'email'         => $email,
				'first_name'    => $first_name,
				'last_name'     => $last_name,
				'avatar'        => $avatar,
				'signature'     => $signature,
				'date_of_birth' => $date_of_birth,
				'sex'           => $sex,
				'location'      => $location,
				'website'       => $website,
				'quote'         => $quote
			])
			->add_submit($this('edit'))
			->add_back('admin/members.html')
			->save();
			
		$form_groups = $this
			->form
			->add_rules([
				'groups' => [
					'type'   => 'checkbox',
					'values' => array_filter($this->groups(), function($group){
						return !$group['auto'] || $group['auto'] == 'neofrag' || $group['users'] !== NULL;
					}),
					'rules'  => 'required'
				]
			])
			->save();
			
		$activities = '';
			
		$sessions = $this
			->table
			->add_columns([
				[
					'content' => function($data){
						return '<div style="text-align: center;">'.user_agent($data['user_agent']).'</div>';
					},
					'size'    => '56px'
				],
				[
					'title'   => $this('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this('referer'),
					'content' => function($data, $loader){
						return $data['referer'] ? urltolink($data['referer']) : $loader->lang('none');
					}
				],
				[
					'title'   => $this('arrival_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				],
				[
					'content' => [
						function($data){
							return button_delete('admin/members/sessions/delete/'.$data['session_id'].'.html');
						}
					]
				]
			])
			->data($this->user->get_sessions($member_id))
			->no_data($this('no_session'))
			->display();
		
		if ($form_member->is_valid($post))
		{
			$this->model()->edit_member(
				$member_id,
				$post['username'],
				$post['email'],
				$post['first_name'],
				$post['last_name'],
				$post['avatar'],
				$post['date_of_birth'],
				$post['sex'],
				$post['location'],
				$post['website'],
				$post['quote'],
				$post['signature']
			);
			
			notify('Membre édité');

			redirect_back('admin/members.html');
		}
		else if ($form_groups->is_valid($post))
		{
			$this->model()->edit_groups(
				$member_id,
				$post['groups']
			);

			notify('Groupes du membre édités');

			redirect_back('admin/members.html');
		}
		
		return new Row(
			new Col(
				new Panel([
					'title'   => $this('edit_member'),
					'icon'    => 'fa-user',
					'content' => $form_member->display(),
					'size'    => 'col-md-12 col-lg-7'
				])
			),
			new Col(
				new Panel([
					'title'   => $this('groups'),
					'icon'    => 'fa-users',
					'content' => $this->load->view('groups', [
						'user_id' => $member_id,
						'form_id' => $form_groups->id
					]),
					'footer'  => '<button class="btn btn-outline btn-primary">'.icon('fa-check').' '.$this('save').'</button>',
					'form'    => TRUE,
					'size'    => 'col-md-12 col-lg-5'
				]),
				/*new Panel(array(
					'title'   => 'Dernières activités',
					'icon'    => 'fa-history',
					'content' => $activities,
					'size'    => 'col-md-12 col-lg-5'
				)),*/
				new Panel([
					'title'   => $this('active_sessions'),
					'icon'    => 'fa-globe',
					'content' => $sessions,
					'size'    => 'col-md-12 col-lg-5'
				])
			)
		);
	}

	public function delete($user_id, $username)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('user_delete_message', $username));

		if ($this->form->is_valid())
		{
			$this->db	->where('user_id', $user_id)
						->update('nf_users', ['deleted' => TRUE]);

			$this->db	->where('user_id', $user_id)
						->delete('nf_sessions');

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _ban($member_id = 0, $username = '')
	{
		$this	->title($this('ban_title'))
				->icon('fa-bomb');
		
		return new Panel([
			'title'   => $this('ban_title'),
			'icon'    => 'fa-bomb',
			'style'   => 'panel-info',
			'content' => $this('unavailable_feature'),
			'size'    => 'col-md-12'
		]);
	}
	
	public function _groups_add()
	{
		$this	->title($this('groups'))
				->subtitle($this('add'))
				->form
				->add_rules('groups')
				->add_back('admin/members.html')
				->add_submit($this('add'));

		if ($this->form->is_valid($post))
		{
			$this->model('groups')->add_group(
				$post['title'],
				$post['color'],
				$post['icon'],
				$this->config->lang
			);

			notify($this('group_added'));

			redirect_back('admin/members.html');
		}

		return new Panel([
			'title'   => $this('add_group'),
			'icon'    => 'fa-users',
			'content' => $this->form->display(),
			'size'    => 'col-md-12'
		]);
	}
	
	public function _groups_edit($group_id, $name, $title, $color, $icon, $auto)
	{
		$this	->title($this('groups'))
				->subtitle($this('edit'))
				->form
				->add_rules('groups', [
					'title' => $title,
					'color' => $color,
					'icon'  => $icon,
					'auto'  => $auto
				])
				->add_back('admin/members.html')
				->add_submit($this('edit'));

		if ($this->form->is_valid($post))
		{
			if ($group_id)
			{
				$this->model('groups')->edit_group(
					$group_id,
					$post['title'],
					$post['color'],
					$post['icon'],
					$this->config->lang,
					$auto
				);
			}
			else
			{
				$this->db->insert('nf_groups', [
					'name'  => $name,
					'color' => $post['color'],
					'icon'  => $post['icon'],
					'auto'  => TRUE
				]);
			}

			notify($this('group_edited'));

			redirect_back('admin/members.html');
		}

		return new Panel([
			'title'   => $this('edit_group_title'),
			'icon'    => 'fa-users',
			'content' => $this->form->display(),
			'size'    => 'col-md-12'
		]);
	}

	public function _groups_delete($group_id, $title)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('group_delete_message', $title));

		if ($this->form->is_valid())
		{
			$this->db	->where('group_id', $group_id)
						->delete('nf_groups');

			$this->access->revoke($group_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _sessions($sessions)
	{
		$this	->title($this('sessions'))
				->subtitle($this('list_active_sessions'))
				->icon('fa-globe')
				->table
				->preprocessing(function($row){
					$user_data = unserialize($row['user_data']);
					
					$row['date']       = $user_data['session']['date'];
					$row['history']    = array_reverse($user_data['session']['history']);
					$row['user_agent'] = $user_data['session']['user_agent'];
					$row['referer']    = $user_data['session']['referer'];
				
					unset($row['user_data']);
					
					return $row;
				})
				->add_columns([
					[
						'content' => function($data){
							return $data['remember_me'] ? '<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="Connexion persistante"></i>' : '<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="Connexion non persistante"></i>';
						},
						'size'    => TRUE,
						'align'   => 'center'
					],
					[
						'title'   => $this('user'),
						'content' => function($data, $loader){
							return $data['user_id'] ? NeoFrag::loader()->user->link($data['user_id'], $data['username']) : '<i>'.$loader->lang('guest').'</i>';
						},
						'search'  => function($data, $loader){
							return $data['user_id'] ? $data['username'] : $loader->lang('guest');
						},
						'sort'  => function($data, $loader){
							return $data['user_id'] ? $data['username'] : $loader->lang('guest');
						}
					],
					[
						'content' => function($data){
							return user_agent($data['user_agent']);
						},
						'size'    => TRUE,
						'align'   => 'center',
						'search'  => function($data){
							return $data['user_agent'];
						},
						'sort'    => function($data){
							return $data['user_agent'];
						}
					],
					[
						'title'   => $this('ip_address'),
						'content' => function($data){
							return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
						},
						'search'  => function($data){
							return $data['ip_address'];
						},
						'sort'    => function($data){
							return $data['ip_address'];
						}
					],
					[
						'title'   => $this('referer'),
						'content' => function($data, $loader){
							return $data['referer'] ? urltolink($data['referer']) : $loader->lang('none');
						},
						'search'  => function($data){
							return $data['user_agent'];
						},
						'sort'    => function($data){
							return $data['user_agent'];
						}
					],
					[
						'title'   => $this('arrival_date'),
						'content' => function($data){
							return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
						},
						'sort'    => function($data){
							return $data['date'];
						}
					],
					[
						'title'   => $this('last_activity'),
						'content' => function($data){
							return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
						},
						'sort'    => function($data){
							return $data['last_activity'];
						}
					],
					[
						'title'   => $this('history'),
						'content' => function($data, $loader){
							$links = implode('<br />', array_map(function($a){
								return '<a href="'.url($a).'">'.$a.'</a>';
							}, $data['history']));

							return '<span data-toggle="popover" title="'.$loader->lang('last_pages_visited').'" data-content="'.utf8_htmlentities($links).'" data-placement="auto" data-html="1">'.icon('fa-history').' '.reset($data['history']).'</span>';
						}
					],
					[
						'content' => [function($data){
							if ($data['user_id'] && $data['session_id'] != NeoFrag::loader()->session('session_id'))
							{
								return button_delete('admin/members/sessions/delete/'.$data['session_id'].'.html');
							}
						}]
					]
				])
				->data($sessions);
		
		return new Panel([
			'title'   => $this('sessions'),
			'icon'    => 'fa-globe',
			'content' => $this->table->display()
		]);
	}
	
	public function _sessions_delete($session_id, $username)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('session_delete_message', $username));

		if ($this->form->is_valid())
		{
			$this->db	->where('session_id', $session_id)
						->delete('nf_sessions');

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/members/controllers/admin.php
*/