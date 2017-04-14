<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($members)
	{
		$this	->title('Membres / Groupes')
				->icon('fa-users');

		$table_groups = $this
			->table()
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
			->table()
			->add_columns([
				[
					'title'   => $this->lang('Membre'),
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
					'title'   => $this->lang('Email'),
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
					'title'   => $this->lang('Groupes'),
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
					'title'   => $this->lang('Inscrit depuis le'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['registration_date'];
					}
				],
				[
					'title'   => $this->lang('Dernière activité'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['last_activity_date']).'">'.time_span($data['last_activity_date']).'</span>';
					},
					'sort'    => function($data){
						return $data['last_activity_date'];
					}
				],
				[
					'content' => [
						function($data){
							return $this->button()
										->tooltip($this->lang('Bannir'))
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
						->heading($this->lang('Groupes'), 'fa-users')
						->body($table_groups->display())
						->footer($this->button_create('admin/user/groups/add', $this->lang('Ajouter un groupe')))
						->size('col-12 col-lg-3')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('Membres'), 'fa-users')
						->body($table_users->display())
						->size('col-12 col-lg-9')
			)
		);
	}

	public function _edit($member_id, $username, $email, $registration_date, $last_activity_date, $admin, $language, $deleted, $avatar, $sex, $first_name, $last_name, $signature, $date_of_birth, $location, $website, $quote, $online)
	{
		$form_member = $this
			->title($this->lang('Édition du membre'))
			->subtitle($username)
			->css('groups')
			->form()
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
			->add_submit($this->lang('Éditer'))
			->add_back('admin/user')
			->save();

		$form_groups = $this
			->form()
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
			->table()
			->add_columns([
				[
					'content' => function($data){
						return '<div style="text-align: center;">'.user_agent($data['user_agent']).'</div>';
					},
					'size'    => '56px'
				],
				[
					'title'   => $this->lang('Adresse IP'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('Site référent'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('Aucun');
					}
				],
				[
					'title'   => $this->lang('Date d\'arrivée'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this->lang('Dernière activité'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
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
			->no_data($this->lang('Aucune session active'))
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
						->heading($this->lang('Édition du membre'), 'fa-user')
						->body($form_member->display())
						->size('col-12 col-lg-7')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('Groupes'), 'fa-users')
						->body($this->view('admin/groups', [
							'user_id' => $member_id,
							'form_id' => $form_groups->token()
						]))
						->size('col-12 col-lg-5'),
				$this	->panel()
						->heading($this->lang('Sessions actives'), 'fa-globe')
						->body($sessions)
						->size('col-12 col-lg-5')
			)
		);
	}

	public function delete($user_id, $username)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le membre <b>%s</b> ?', $username));

		if ($this->form()->is_valid())
		{
			$this->db	->where('id', $user_id)
						->update('nf_user', ['deleted' => TRUE]);

			$this->db	->where('user_id', $user_id)
						->delete('nf_session');

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _ban($member_id = 0, $username = '')
	{
		$this	->title($this->lang('Bannissement'))
				->icon('fa-bomb');

		return $this->panel()
					->heading($this->lang('Bannissement'), 'fa-bomb')
					->body($this->lang('Cette fonctionnalité n\'est pas disponible pour l\'instant'))
					->color('info')
					->size('col-12');
	}

	public function _groups_add()
	{
		$this	->title($this->lang('Groupes'))
				->subtitle($this->lang('Ajouter'))
				->form()
				->add_rules('groups')
				->add_back('admin/user')
				->add_submit($this->lang('Ajouter'));

		if ($this->form()->is_valid($post))
		{
			$this->model('groups')->add_group(
				$post['title'],
				$post['color'],
				$post['icon'],
				in_array('on', $post['hidden']),
				$this->config->lang->info()->name
			);

			notify($this->lang('Groupe ajouté'));

			redirect_back('admin/user');
		}

		return $this->panel()
					->heading($this->lang('Ajouter un groupe'), 'fa-users')
					->body($this->form()->display())
					->size('col-12');
	}

	public function _groups_edit($group_id, $name, $title, $color, $icon, $hidden, $auto)
	{
		$this	->title($this->lang('Groupes'))
				->subtitle($this->lang('Éditer'))
				->form()
				->add_rules('groups', [
					'title'  => $title,
					'color'  => $color,
					'icon'   => $icon,
					'hidden' => $hidden,
					'auto'   => $auto
				])
				->add_back('admin/user')
				->add_submit($this->lang('Éditer'));

		if ($this->form()->is_valid($post))
		{
			if ($group_id)
			{
				$this->model('groups')->edit_group(
					$group_id,
					!$auto ? $post['title'] : NULL,
					$post['color'],
					$post['icon'],
					in_array('on', $post['hidden']),
					$this->config->lang->info()->name,
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

			notify($this->lang('Groupe modifié'));

			redirect_back('admin/user');
		}

		return $this->panel()
					->heading($this->lang('Éditer un groupe'), 'fa-users')
					->body($this->form()->display())
					->size('col-12');
	}

	public function _groups_delete($group_id, $title)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer le groupe <b>%s</b> ?', $title));

		if ($this->form()->is_valid())
		{
			$this->db	->where('group_id', $group_id)
						->delete('nf_groups');

			$this->access->revoke($group_id);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _sessions($sessions)
	{
		$table = $this	->title($this->lang('Sessions'))
						->subtitle($this->lang('Liste des sessions actives'))
						->icon('fa-globe')
						->table()
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
								'title'   => $this->lang('Utilisateur'),
								'content' => function($data){
									return $data['user_id'] ? NeoFrag()->user->link($data['user_id'], $data['username']) : '<i>'.$this->lang('Visiteur').'</i>';
								},
								'search'  => function($data){
									return $data['user_id'] ? $data['username'] : $this->lang('Visiteur');
								},
								'sort'  => function($data){
									return $data['user_id'] ? $data['username'] : $this->lang('Visiteur');
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
								'title'   => $this->lang('Adresse IP'),
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
								'title'   => $this->lang('Site référent'),
								'content' => function($data){
									return $data['referer'] ? urltolink($data['referer']) : $this->lang('Aucun');
								},
								'search'  => function($data){
									return $data['user_agent'];
								},
								'sort'    => function($data){
									return $data['user_agent'];
								}
							],
							[
								'title'   => $this->lang('Date d\'arrivée'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.time_span($data['date']).'</span>';
								},
								'sort'    => function($data){
									return $data['date'];
								}
							],
							[
								'title'   => $this->lang('Dernière activité'),
								'content' => function($data){
									return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
								},
								'sort'    => function($data){
									return $data['last_activity'];
								}
							],
							[
								'title'   => $this->lang('Historique'),
								'content' => function($data){
									$links = implode('<br />', array_map(function($a){
										return '<a href="'.url($a).'">'.$a.'</a>';
									}, $data['history']));

									return '<span data-toggle="popover" title="'.$this->lang('Dernières pages visitées').'" data-content="'.utf8_htmlentities($links).'" data-placement="auto" data-html="1">'.icon('fa-history').' '.reset($data['history']).'</span>';
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
					->heading($this->lang('Sessions'), 'fa-globe')
					->body($table->display());
	}

	public function _sessions_delete($session_id, $username)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la session de l\'utilisateur <b>%s</b> ?', $username));

		if ($this->form()->is_valid())
		{
			$this->db	->where('id', $session_id)
						->delete('nf_session');

			return 'OK';
		}

		return $this->form()->display();
	}
}
