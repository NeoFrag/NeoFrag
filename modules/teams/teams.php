<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Teams;

use NF\NeoFrag\Addons\Module;

class Teams extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Équipes'),
			'description' => '',
			'icon'        => 'fas fa-headset',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'routes'      => [
				//Index
				'{id}/{url_title}'                           => '_team',

				//Admin
				'admin/{id}/{url_title*}'                    => '_edit',
				'admin/roles/add'                            => '_roles_add',
				'admin/roles/{id}/{url_title*}'              => '_roles_edit',
				'admin/roles/delete/{id}/{url_title}'        => '_roles_delete',
				'admin/players/delete/{id}/{url_title}/{id}' => '_players_delete',
				'admin/ajax/roles/sort'                      => '_roles_sort'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_checkbox('teams_display_matches')
										->data(['on' => 'Afficher les matchs réalisés'])
										->value([$this->config->teams_display_matches ? 'on' : NULL])
							)
							->success(function($data){
								$this->config('teams_display_matches', $data['teams_display_matches']);
								notify('Configuration modifiée');
								refresh();
							});
			}
		];
	}

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Équipes',
						'icon'   => 'fas fa-headset',
						'access' => [
							'add_teams' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_teams' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_teams' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Rôles',
						'icon'   => 'fas fa-sitemap',
						'access' => [
							'add_teams_roles' => [
								'title' => 'Ajouter un rôle',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_teams_roles' => [
								'title' => 'Modifier un rôle',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_teams_roles' => [
								'title' => 'Supprimer un rôle',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}

	public function groups()
	{
		$teams = NeoFrag()->db	->select('t.team_id', 't.name', 'tl.title')
										->from('nf_teams t')
										->join('nf_teams_lang tl',  'tl.team_id = t.team_id')
										->where('tl.lang', NeoFrag()->config->lang->info()->name)
										->get();

		$groups = [];

		foreach ($teams as $team)
		{
			$groups[$team['team_id']] = [
				'name'  => $team['name'],
				'title' => $team['title'],
				'users' => $this->db()->select('u.id')->from('nf_teams_users tu')->join('nf_user u', 'tu.user_id = u.id', 'INNER')->where('tu.team_id', $team['team_id'])->where('u.deleted', FALSE)->get()
			];
		}

		return $groups;
	}
}
