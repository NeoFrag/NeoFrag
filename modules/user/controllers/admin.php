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

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('Groupes'), 'fa-users')
						->body($table_groups->display())
						->footer($this->button_create('admin/user/groups/add', $this->lang('Ajouter un groupe')))
						->size('col-12 col-lg-3')
			),
			$this->col(
				$this	->table2($members)
						->col('Membre', function($user){
							return $user->link();
						})
						->col('Email', function($user){
							return '<a href="mailto:'.$user->email.'">'.$user->email.'</a>';
						})
						->col('Groupes', 'groups')
						->col('Inscrit depuis le', 'registration_date')
						->col('Dernière activité', 'last_activity_date')
						->update()
						->delete()
						->panel()
						->title('Membres', 'fa-users')
						->size('col-12 col-lg-9')
			)
		);
	}

	public function edit($user)
	{
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

		if ($form_groups->is_valid($post))
		{
			$this->db	->where('user_id', $user->id)
						->delete('nf_users_groups');

			$this->db	->where('user_id', $user->id)
						->update('nf_users', [
							'admin' => FALSE
						]);

			if (in_array('admins', $post['groups']))
			{
				$this->db	->where('user_id', $user->id)
							->update('nf_users', [
								'admin' => TRUE
							]);
			}

			foreach ($post['groups'] as $group_id)
			{
				if ($this->groups()[$group_id]['auto'])
				{
					continue;
				}

				$this->db->insert('nf_users_groups', [
					'user_id'  => $user->id,
					'group_id' => $group_id
				]);
			}

			notify('Groupes du membre édités');

			redirect_back('admin/user');
		}

		return $this->title($this->lang('Édition du membre'))
					->subtitle($user->username)
					->css('groups')
					->row()
					->append(
						$this	->col()
								->size('col-12 col-lg-7')
								->append(
									$this	->form2('username email new_password', $user)
											->success(function($user){
												$user->update();
												notify($this->lang('Membre modifié'));
												refresh();
											})
											->panel()
											->title('Membre')
								)
								->append(
									$this	->form2('profile', $user->profile())
											->success(function($profile){
												$profile->update();
												notify($this->lang('Profil modifié'));
												refresh();
											})
											->panel()
											->title('Profil')
								)
								->append(
									$this	->form2('profile_socials', $user->profile())
											->success(function($profile){
												$profile->update();
												notify($this->lang('Profil modifié'));
												refresh();
											})
											->panel()
											->title('Liens', 'fa-globe')
								)
					)
					->append($this->col(
						$this	->panel()
								->heading($this->lang('Groupes'), 'fa-users')
								->body($this->view('admin/groups', [
									'user_id' => $user->id,
									'form_id' => $form_groups->token()
								]))
								->size('col-12 col-lg-5'),
						$this	->table2('session', $user->sessions())
								->panel()
								->title($this->lang('Sessions actives'), 'fa-globe')
								->size('col-12 col-lg-5')
					));
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
		return $this->title($this->lang('Sessions'))
					->subtitle($this->lang('Liste des sessions actives'))
					->icon('fa-globe')
					->table2('session', $sessions)
					->panel();
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
