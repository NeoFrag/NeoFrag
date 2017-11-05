<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_teams extends Module
{
	public $title       = '{lang teams_title}';
	public $description = '';
	public $icon        = 'fa-gamepad';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = 'gaming';
	public $routes      = [
		//Index
		'{id}/{url_title}'                           => '_team',

		//Admin
		'admin/{id}/{url_title*}'                    => '_edit',
		'admin/roles/add'                            => '_roles_add',
		'admin/roles/{id}/{url_title*}'              => '_roles_edit',
		'admin/roles/delete/{id}/{url_title}'        => '_roles_delete',
		'admin/players/delete/{id}/{url_title}/{id}' => '_players_delete',
		'admin/ajax/roles/sort'                      => '_roles_sort'
	];

	public function groups()
	{
		$teams = NeoFrag()->db	->select('t.team_id', 't.name', 'tl.title', 'GROUP_CONCAT(tu.user_id) AS users')
										->from('nf_teams t')
										->join('nf_teams_lang tl',  'tl.team_id = t.team_id')
										->join('nf_teams_users tu', 'tu.team_id = t.team_id')
										->where('tl.lang', NeoFrag()->config->lang)
										->group_by('t.team_id')
										->get();

		$groups = [];

		foreach ($teams as $team)
		{
			$groups[$team['team_id']] = [
				'name'  => $team['name'],
				'title' => $team['title'],
				'users' => array_filter(array_map('intval', explode(',', $team['users'])))
			];
		}

		return $groups;
	}
}
