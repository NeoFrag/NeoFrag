<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_c_admin extends Controller_Module
{
	public function index($members)
	{
		$this	->title('Membres / Groupes')
				->icon('fa-users');

		$table_groups = $this
			->table
			->add_columns([
				[
					'content' => function($data){
						return $data['auto'] != 'neofrag' ? $this->button_sort($data['data_id'], 'admin/ajax/user/groups/sort') : NULL;
					},
					'size'    => TRUE
				],
				[
					'content' => function($data){
						return NeoFrag()->groups->display($data['data_id']);
					},
					'search'  => function($data){
						return NeoFrag()->groups->display($data['data_id'], FALSE, FALSE);
					}
				],
				[
					'content' => function($data){
						return $data['hidden'] ? $this->button()->icon('fa-eye-slash')->tooltip('Groupe caché') : NULL;
					},
					'size'    => TRUE
				],
				[
					'content' => function($data){
							return $this->button_update('admin/user/groups/edit/'.$data['url']);
					},
					'size'    => TRUE
				],
				[
					'content' => function($data){
						if (!$data['auto'])
						{
							return $this->button_delete('admin/user/groups/delete/'.$data['url']);
						}
					},
					'size'    => TRUE
				]
			])
			->data($this->groups())
			->pagination(FALSE)
			->save();

		$table_users = $this
			->table
			->add_columns([
				[
					'title'   => $this->lang('member'),
					'content' => function($data){
						return NeoFrag()->user->link($data['user_id'], $data['username']);
					},
					'sort'    => function($data){
						return $data['username'];
					},
					'search'  => function($data){
						return $data['username'];
					}
				],
				[
					'title'   => $this->lang('email'),
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
					'title'   => $this->lang('groups'),
					'content' => function($data){
						return NeoFrag()->groups->user_groups($data['user_id']);
					},
					'sort'    => function($data){
						return NeoFrag()->groups->user_groups($data['user_id'], FALSE);
					},
					'search'  => function($data){
						return NeoFrag()->groups->user_groups($data['user_id'], FALSE);
					}
				],
				[
					'title'   => $this->lang('registration_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['registration_date'];
					}
				],
				[
					'title'   => $this->lang('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['last_activity_date']).'">'.time_span($data['last_activity_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['last_activity_date'];
					}
				],
				[
					'content' => [
						function($data){
							return $this->button()
										->tooltip($this->lang('ban'))
										->icon('fa-ban')
										->url('admin/user/ban/'.$data['user_id'].'/'.url_title($data['username']))
										->color('warning')
										->compact()
										->outline();
						},
						function($data){
							return $this->button_update('admin/user/'.$data['user_id'].'/'.url_title($data['username']));
						},
						function($data){
							return $this->button_delete('admin/user/delete/'.$data['user_id'].'/'.url_title($data['username']));
						}
					]
				]
			])
			->data($members)
			->save();

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('groups'), 'fa-users')
						->body($table_groups->display())
						->footer($this->button_create('admin/user/groups/add', $this->lang('add_group')))
						->size('col-md-12 col-lg-3')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('members'), 'fa-users')
						->body($table_users->display())
						->size('col-md-12 col-lg-9')
			)
		);
	}

	public function _edit($member_id, $username, $email, $registration_date, $last_activity_date, $admin, $language, $deleted, $avatar, $sex, $first_name, $last_name, $signature, $date_of_birth, $location, $website, $quote, $online)
	{
		$form_member = $this
			->title($this->lang('edit_member'))
			->subtitle($username)
			->css('groups')
			->form
			->add_rules('user', [
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
			->add_submit($this->lang('edit'))
			->add_back('admin/user')
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
					'title'   => $this->lang('ip_address'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('referer'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('none');
					}
				],
				[
					'title'   => $this->lang('arrival_date'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this->lang('last_activity'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				],
				[
					'content' => [
						function($data){
							return $this->button_delete('admin/user/sessions/delete/'.$data['session_id']);
						}
					]
				]
			])
			->data($this->user->get_sessions($member_id))
			->no_data($this->lang('no_session'))
			->display();

		if ($form_member->is_valid($post))
		{
			$this->model()->edit_user(
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
				$post['signature'],
				$member_id
			);

			notify('Membre édité');

			redirect_back('admin/user');
		}
		else if ($form_groups->is_valid($post))
		{
			$this->model()->edit_groups(
				$member_id,
				$post['groups']
			);

			notify('Groupes du membre édités');

			redirect_back('admin/user');
		}

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('edit_member'), 'fa-user')
						->body($form_member->display())
						->size('col-md-12 col-lg-7')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('groups'), 'fa-users')
						->body($this->view('admin/groups', [
							'user_id' => $member_id,
							'form_id' => $form_groups->token()
						]))
						->size('col-md-12 col-lg-5'),
				$this	->panel()
						->heading($this->lang('active_sessions'), 'fa-globe')
						->body($sessions)
						->size('col-md-12 col-lg-5')
			)
		);
	}

	public function delete($user_id, $username)
	{
		$this	->title($this->lang('delete_confirmation'))
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('user_delete_message', $username));

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
		$this	->title($this->lang('ban_title'))
				->icon('fa-bomb');

		return $this->panel()
					->heading($this->lang('ban_title'), 'fa-bomb')
					->body($this->lang('unavailable_feature'))
					->color('info')
					->size('col-md-12');
	}

	public function _groups_add()
	{
		$this	->title($this->lang('groups'))
				->subtitle($this->lang('add'))
				->form
				->add_rules('groups')
				->add_back('admin/user')
				->add_submit($this->lang('add'));

		if ($this->form->is_valid($post))
		{
			$this->model('groups')->add_group(
				$post['title'],
				$post['color'],
				$post['icon'],
				in_array('on', $post['hidden']),
				$this->config->lang
			);

			notify($this->lang('group_added'));

			redirect_back('admin/user');
		}

		return $this->panel()
					->heading($this->lang('add_group'), 'fa-users')
					->body($this->form->display())
					->size('col-md-12');
	}

	public function _groups_edit($group_id, $name, $title, $color, $icon, $hidden, $auto)
	{
		$this	->title($this->lang('groups'))
				->subtitle($this->lang('edit'))
				->form
				->add_rules('groups', [
					'title'  => $title,
					'color'  => $color,
					'icon'   => $icon,
					'hidden' => $hidden,
					'auto'   => $auto
				])
				->add_back('admin/user')
				->add_submit($this->lang('edit'));

		if ($this->form->is_valid($post))
		{
			if ($group_id)
			{
				$this->model('groups')->edit_group(
					$group_id,
					!$auto ? $post['title'] : NULL,
					$post['color'],
					$post['icon'],
					in_array('on', $post['hidden']),
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

			notify($this->lang('group_edited'));

			redirect_back('admin/user');
		}

		return $this->panel()
					->heading($this->lang('edit_group_title'), 'fa-users')
					->body($this->form->display())
					->size('col-md-12');
	}

	public function _groups_delete($group_id, $title)
	{
		$this	->title($this->lang('delete_confirmation'))
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('group_delete_message', $title));

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
		$this	->title($this->lang('sessions'))
				->subtitle($this->lang('list_active_sessions'))
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
						'title'   => $this->lang('user'),
						'content' => function($data){
							return $data['user_id'] ? NeoFrag()->user->link($data['user_id'], $data['username']) : '<i>'.$this->lang('guest').'</i>';
						},
						'search'  => function($data){
							return $data['user_id'] ? $data['username'] : $this->lang('guest');
						},
						'sort'  => function($data){
							return $data['user_id'] ? $data['username'] : $this->lang('guest');
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
						'title'   => $this->lang('ip_address'),
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
						'title'   => $this->lang('referer'),
						'content' => function($data){
							return $data['referer'] ? urltolink($data['referer']) : $this->lang('none');
						},
						'search'  => function($data){
							return $data['user_agent'];
						},
						'sort'    => function($data){
							return $data['user_agent'];
						}
					],
					[
						'title'   => $this->lang('arrival_date'),
						'content' => function($data){
							return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.time_span($data['date']).'</span>';
						},
						'sort'    => function($data){
							return $data['date'];
						}
					],
					[
						'title'   => $this->lang('last_activity'),
						'content' => function($data){
							return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
						},
						'sort'    => function($data){
							return $data['last_activity'];
						}
					],
					[
						'title'   => $this->lang('history'),
						'content' => function($data){
							$links = implode('<br />', array_map(function($a){
								return '<a href="'.url($a).'">'.$a.'</a>';
							}, $data['history']));

							return '<span data-toggle="popover" title="'.$this->lang('last_pages_visited').'" data-content="'.utf8_htmlentities($links).'" data-placement="auto" data-html="1">'.icon('fa-history').' '.reset($data['history']).'</span>';
						}
					],
					[
						'content' => [function($data){
							if ($data['user_id'] && $data['session_id'] != NeoFrag()->session('session_id'))
							{
								return $this->button_delete('admin/user/sessions/delete/'.$data['session_id']);
							}
						}]
					]
				])
				->data($sessions);

		return $this->panel()
					->heading($this->lang('sessions'), 'fa-globe')
					->body($this->table->display());
	}

	public function _sessions_delete($session_id, $username)
	{
		$this	->title($this->lang('delete_confirmation'))
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('session_delete_message', $username));

		if ($this->form->is_valid())
		{
			$this->db	->where('session_id', $session_id)
						->delete('nf_sessions');

			return 'OK';
		}

		echo $this->form->display();
	}
}
