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
		$teams = NeoFrag::loader()->db	->select('t.team_id', 't.name', 'tl.title', 'GROUP_CONCAT(tu.user_id) AS users')
										->from('nf_teams t')
										->join('nf_teams_lang tl',  'tl.team_id = t.team_id')
										->join('nf_teams_users tu', 'tu.team_id = t.team_id')
										->where('tl.lang', NeoFrag::loader()->config->lang)
										->group_by('t.team_id')
										->get();
		
		$groups = [];
		
		foreach ($teams as $team)
		{
			$groups[$team['team_id']] = [
				'name'  => $team['name'],
				'title' => $team['title'],
				'users' => array_map('intval', explode(',', $team['users']))
			];
		}
		
		return $groups;
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/teams/teams.php
*/