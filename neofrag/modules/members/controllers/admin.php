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

class m_members_c_admin extends Controller_Module
{
	public function index($members)
	{
		$table_groups = $this
			->load->library('table')
			->add_columns(array(
				array(
					'content' => '<?php echo $this->groups->display($data[\'data_id\']); ?>',
					'search'  => '<?php echo $this->groups->display($data[\'data_id\'], FALSE, FALSE); ?>',
					'sort'    => '<?php echo $this->groups->display($data[\'data_id\'], FALSE, FALSE); ?>'
				),
				array(
					'content' => array(
						button_edit('{base_url}admin/members/groups/edit/{url_title(data_id)}/{url_title(title)}.html'),
						'<?php if (!$data[\'auto\']) echo \''.button_delete($this->config->base_url.'admin/members/groups/delete/\'.$data[\'data_id\'].\'/\'.url_title($data[\'title\']).\'.html').'\'; ?>'
					)
				)
			))
			->data($this->groups())
			->pagination(FALSE)
			->save();

		$table_users = $this
			->table
			->add_columns(array(
				array(
					'title'   => 'Membre',
					'content' => '<?php echo $this->user->link($data[\'user_id\'], $data[\'username\']); ?>',
					'sort'    => '{username}',
					'search'  => '{username}'
				),
				array(
					'title'   => 'Email',
					'content' => '<a href="mailto:{email}">{email}</a>',
					'sort'    => '{email}',
					'search'  => '{email}'
				),
				array(
					'title'   => 'Groupes',
					'content' => '<?php echo $this->groups->user_groups($data[\'user_id\']); ?>',
					'sort'    => '<?php echo $this->groups->user_groups($data[\'user_id\'], FALSE); ?>',
					'search'  => '<?php echo $this->groups->user_groups($data[\'user_id\'], FALSE); ?>'
				),
				array(
					'title'   => 'Date d\'inscription',
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'registration_date\']); ?>">{time_span(registration_date)}</span>',
					'sort'    => '{registration_date}'
				),
				array(
					'title'   => 'Dernière activité',
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'last_activity_date\']); ?>">{time_span(last_activity_date)}</span>',
					'sort'    => '{last_activity_date}'
				),
				array(
					'content' => array(
						button('{base_url}admin/members/ban/{user_id}/{url_title(username)}.html', 'fa-ban', 'Bannir', 'warning'),
						button_edit('{base_url}admin/members/{user_id}/{url_title(username)}.html'),
						button_delete('{base_url}admin/members/delete/{user_id}/{url_title(username)}.html')
					)
				)
			))
			->data($members)
			->save();
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Groupes',
					'icon'    => 'fa-users',
					'content' => $table_groups->display(),
					'footer'  => button_add('{base_url}admin/members/groups/add.html', 'Ajouter un groupe'),
					'size'    => 'col-md-12 col-lg-3'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Membres',
					'icon'    => 'fa-users',
					'content' => $table_users->display(),
					'size'    => 'col-md-12 col-lg-9'
				))
			)
		);
	}

	public function _edit($member_id, $username, $email, $groups, $first_name, $last_name, $avatar, $signature, $date_of_birth, $sex, $location, $website, $quote)
	{
		$form_member = $this
			->subtitle('Éditer')
			->css('groups')
			->load->library('form')
			->add_rules('members', array(
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
			))
			->add_submit('Éditer')
			->add_back('admin/members.html')
			->save();
			
		$form_groups = $this
			->form
			->add_rules(array(
				'groups' => array(
					'type'   => 'checkbox',
					'values' => array_filter($this->groups(), function($group){
						return !$group['auto'] || $group['auto'] == 'neofrag' || !is_null($group['users']);
					}),
					'rules'  => 'required'
				)
			))
			->save();
			
		$activities = '';/*$this
			->load->library('table')
			->add_columns(array(
				array(
					'content' => '<div style="text-align: center;"><?php echo user_agent($data[\'user_agent\']); ?></div>',
					'size'    => '56px'
				),
				array(
					'content' => '<?php echo geolocalisation($data[\'ip_address\']); ?><span data-toggle="tooltip" data-original-title="{host_name}">{ip_address}</span>'
				),
				array(
					'content' => '<?php echo ($data[\'referer\']) ? urltolink($data[\'referer\']) : \'Aucun\'; ?>'
				),
				array(
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'date\']); ?>">{time_span(date)}</span>'
				),
				array(
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'last_activity\']); ?>">{time_span(last_activity)}</span>'
				)
			))
			->data($this->user->get_sessions($member_id))
			->no_data('Aucune session active')
			->display();*/
			
		$sessions = $this
			->load->library('table')
			->add_columns(array(
				array(
					'content' => '<div style="text-align: center;"><?php echo user_agent($data[\'user_agent\']); ?></div>',
					'size'    => '56px'
				),
				array(
					'title'   => 'Adresse IP',
					'content' => '<?php echo geolocalisation($data[\'ip_address\']); ?><span data-toggle="tooltip" data-original-title="{host_name}">{ip_address}</span>'
				),
				array(
					'title'   => 'Site référent',
					'content' => '<?php echo ($data[\'referer\']) ? urltolink($data[\'referer\']) : \'Aucun\'; ?>'
				),
				array(
					'title'   => 'Date d\'arrivée',
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'date\']); ?>">{time_span(date)}</span>'
				),
				array(
					'title'   => 'Dernière activité',
					'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'last_activity\']); ?>">{time_span(last_activity)}</span>'
				),
				array(
					'content' => array(
						button_delete('{base_url}admin/members/sessions/delete/{session_id}.html')
					)
				)
			))
			->data($this->user->get_sessions($member_id))
			->no_data('Aucune session active')
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
			
			//add_alert('succes', 'membre édité');

			redirect_back('admin/members.html');
		}
		else if ($form_groups->is_valid($post))
		{
			$this->model()->edit_groups(
				$member_id,
				$post['groups']
			);

			//add_alert('succes', 'groupes du membre édités');

			redirect_back('admin/members.html');
		}
		
		return new Row(
			new Col(
				new Panel(array(
					'title'   => 'Éditer un membre',
					'icon'    => 'fa-user',
					'content' => $form_member->display(),
					'size'    => 'col-md-12 col-lg-7'
				))
			),
			new Col(
				new Panel(array(
					'title'   => 'Groupes',
					'icon'    => 'fa-users',
					'content' => $this->load->view('groups', array(
						'user_id' => $member_id,
						'form_id' => $form_groups->id
					)),
					'footer'  => '<button class="btn btn-outline btn-primary"><i class="fa fa-check"></i> Valider</button>',
					'form'    => TRUE,
					'size'    => 'col-md-12 col-lg-5'
				)),
				/*new Panel(array(
					'title'   => 'Dernières activités',
					'icon'    => 'fa-history',
					'content' => $activities,
					'size'    => 'col-md-12 col-lg-5'
				)),*/
				new Panel(array(
					'title'   => 'Sessions actives',
					'icon'    => 'fa-globe',
					'content' => $sessions,
					'size'    => 'col-md-12 col-lg-5'
				))
			)
		);
	}

	public function delete($user_id, $username)
	{
		$this	->title('Confirmation de suppression')
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le membre <b>'.$username.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->db	->where('user_id', $user_id)
						->update('nf_users', array('deleted' => TRUE));

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _ban($member_id = 0, $username = '')
	{
		$this	->title('Bannissement')
				->icon('fa-bomb');
		
		return new Panel(array(
			'title'   => 'Bannissement',
			'icon'    => 'fa-bomb',
			'style'   => 'panel-info',
			'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant.',
			'size'    => 'col-md-12'
		));
	}
	
	public function _permissions()
	{
		$this	->title('Permissions')
				->icon('fa-unlock-alt');
		
		return new Panel(array(
			'title'   => 'Gestion des permissions',
			'icon'    => 'fa-unlock-alt',
			'style'   => 'panel-info',
			'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant.',
			'size'    => 'col-md-12'
		));
	}
	
	public function _groups_add()
	{
		$this	->title('Groupes')
				->subtitle('Ajouter')
				->load->library('form')
				->add_rules('groups')
				->add_back('admin/members.html')
				->add_submit('Ajouter');

		if ($this->form->is_valid($post))
		{
			$this->model('groups')->add_group(
				$post['title'],
				$post['color'],
				$post['icon'],
				$this->config->lang
			);

			add_alert('Succes', 'Groupe ajouté');

			redirect_back('admin/members.html');
		}

		return new Panel(array(
			'title'   => 'Ajouter un groupe',
			'icon'    => 'fa-users',
			'content' => $this->form->display(),
			'size'    => 'col-md-12'
		));
	}
	
	public function _groups_edit($group_id, $title, $color, $icon, $auto)
	{
		$this	->title('Groupes')
				->subtitle('Éditer')
				->load->library('form')
				->add_rules('groups', array(
					'title' => $title,
					'color' => $color,
					'icon'  => $icon,
					'auto'  => $auto
				))
				->add_back('admin/members.html')
				->add_submit('Éditer');

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
				$this->model('groups')->add_group(
					$post['title'],
					$post['color'],
					$post['icon'],
					$this->config->lang
				);
			}

			add_alert('Succes', 'Groupe modifié');

			redirect_back('admin/members.html');
		}

		return new Panel(array(
			'title'   => 'Éditer un groupe',
			'icon'    => 'fa-users',
			'content' => $this->form->display(),
			'size'    => 'col-md-12'
		));
	}

	public function _groups_delete($group_id, $title)
	{
		$this	->title('Confirmation de suppression')
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer le groupe <b>'.$title.'</b> ?');

		if ($this->form->is_valid())
		{
			$this->db	->where('group_id', $group_id)
						->delete('nf_groups');

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _sessions($sessions)
	{
		$this	->title('Sessions')
				->subtitle('Liste des sessions actives')
				->icon('fa-globe')
				->load->library('table')
				->preprocessing(function($row){
					$user_data = unserialize($row['user_data']);
					
					$row['date']       = $user_data['session']['date'];
					$row['history']    = array_reverse($user_data['session']['history']);
					$row['user_agent'] = $user_data['session']['user_agent'];
					$row['referer']    = $user_data['session']['referer'];
				
					unset($row['user_data']);
					
					return $row;
				})
				->add_columns(array(
					array(
						'content' => '<?php echo $data[\'remember_me\'] ? \'<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="Connexion persistante"></i>\' : \'<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="Connexion non persistante"></i>\' ?>',
						'size'    => TRUE,
						'align'   => 'center'
					),
					array(
						'title'   => 'Utilisateur',
						'content' => function($data){
							return $data['user_id'] ? NeoFrag::loader()->user->link($data['user_id'], $data['username']) : '<i>Visiteur</i>';
						},
						'search'  => function($data){
							return $data['user_id'] ? $data['username'] : 'Visiteur';
						},
						'sort'  => function($data){
							return $data['user_id'] ? $data['username'] : 'Visiteur';
						}
					),
					array(
						'content' => '<?php echo user_agent($data[\'user_agent\']); ?>',
						'size'    => TRUE,
						'align'   => 'center',
						'search'  => '{user_agent}',
						'sort'    => '{user_agent}'
					),
					array(
						'title'   => 'Adresse IP',
						'content' => '<?php echo geolocalisation($data[\'ip_address\']); ?><span data-toggle="tooltip" data-original-title="{host_name}">{ip_address}</span>',
						'search'  => '{ip_address}',
						'sort'    => '{ip_address}'
					),
					array(
						'title'   => 'Site référent',
						'content' => '<?php echo ($data[\'referer\']) ? urltolink($data[\'referer\']) : \'Aucun\'; ?>',
						'search'  => '{referer}',
						'sort'    => '{referer}'
					),
					array(
						'title'   => 'Date d\'arrivée',
						'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'date\']); ?>">{time_span(date)}</span>',
						'sort'    => '{date}'
					),
					array(
						'title'   => 'Dernière activité',
						'content' => '<span data-toggle="tooltip" title="<?php echo timetostr($NeoFrag->lang(\'date_time_long\'), $data[\'last_activity\']); ?>">{time_span(last_activity)}</span>',
						'sort'    => '{last_activity}'
					),
					array(
						'title'   => 'Historique',
						'content' => function($data){
							$links = implode('<br />', array_map(function($a){
								return '<a href="'.NeoFrag::loader()->config->base_url.$a.'">'.$a.'</a>';
							}, $data['history']));

							return '<span data-toggle="popover" title="Dernières pages visitées" data-content="'.utf8_htmlentities($links).'" data-placement="auto" data-html="1">{fa-icon history} '.reset($data['history']).'</span>';
						}
					),
					array(
						'content' => array(function($data){
							if ($data['user_id'] && $data['session_id'] != NeoFrag::loader()->session('session_id'))
							{
								return button_delete($this->config->base_url.'admin/members/sessions/delete/'.$data['session_id'].'.html');
							}
						})
					)
				))
				->data($sessions);
		
		return new Panel(array(
			'title'   => 'Sessions',
			'icon'    => 'fa-globe',
			'content' => $this->table->display()
		));
	}
	
	public function _sessions_delete($session_id, $username)
	{
		$this	->title('Confirmation de suppression')
				->load->library('form')
				->confirm_deletion('Confirmation de suppression', 'Êtes-vous sûr(e) de vouloir supprimer la session de l\'utilisateur <b>'.$username.'</b> ?');

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
NeoFrag Alpha 0.1
./neofrag/modules/members/controllers/admin.php
*/