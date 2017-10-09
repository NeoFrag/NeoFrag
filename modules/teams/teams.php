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
			'icon'        => 'fa-gamepad',
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
				'users' => $this->db()->select('user.id')->from('nf_teams_users tu')->join('nf_user u', 'tu.user_id = u.id', 'INNER')->where('tu.team_id', $team['team_id'])->where('u.deleted', FALSE)->get()
			];
		}

		return $groups;
	}
}
