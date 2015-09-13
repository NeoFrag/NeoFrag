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

class m_access_c_admin extends Controller_Module
{
	public function index($objects, $modules, $tab)
	{
		if (!$modules)
		{
			return new Panel(array(
				'title'   => 'Permissions',
				'icon'    => 'fa-unlock-alt',
				'content' => 'Il n\'y a aucune permission à administrer'
			));
		}
		
		$this	->js('access')
				->load->library('tab');

		foreach ($modules as $module_name => $module)
		{
			list($title, $icon, $type, $access) = $module;
			$this->tab->add_tab($module_name, icon($icon).' '.$title, '_tab_index', $objects, $title, $module_name, $type, $access);
		}

		return new Panel(array(
			'content' => $this->tab->display($tab)
		));
	}
	
	public function _tab_index($objects, $title, $module, $type, $all_access)
	{
		$this	->subtitle($title)
				->load->library('table')
				->add_columns(array(
					array(
						'title'   => 'Nom',
						'content' => function($data){
							return $data['title'];
						}
					)
				));

		foreach ($all_access['access'] as $a)
		{
			foreach ($a['access'] as $action => $access)
			{
				$this	->table
						->add_columns(array(
							array(
								'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$access['title'].'">'.icon($access['icon']).'</div>',
								'content' => function($data) use ($module, $action){
									return NeoFrag::loader()->access->count($module, $action, $data['id']);
								},
								'class'   => 'col-md-1'
							)
						));
			}
		}
		
		$this	->table
				->add_columns(array(
					array(
						'content' => array(
							function($data) use ($module, $type){
								return button(NULL, 'fa-refresh', 'Réinitialiser', 'info', 'access-reset', array(
									'module' => $module,
									'type'   => $type,
									'id'     => $data['id']
								));
							},
							/*function(){
								return button('#', 'fa-copy', 'Glissez pour copier', 'primary');
							},*/
							function($data) use ($module, $type){
								return button_access($data['id'], $type, $module, 'Éditer');
							}
						)
					)
				))
				->data($objects);

		echo $this->table->display();
	}
	
	public function _edit($module, $type, $access, $id, $title = NULL)
	{
		$this	->title($module->name)
				->subtitle($title ?: 'Gestion des permissions')
				->icon($module->icon)
				->css('access')
				->js('access')
				->css('neofrag.table')
				->js('neofrag.table');
		
		return array(
			new Row(
				new Col(
					new Panel(array(
						'title'   => 'Liste des permissions<div class="pull-right">'.button(NULL, 'fa-refresh', 'Réinitialiser toutes les permissions', 'info', 'access-reset', array(
							'module' => $module->get_name(),
							'type'   => $type,
							'id'     => $id
						)).'</div>',
						'icon'    => 'fa-unlock-alt',
						'content' => $this->load->view('index', array(
							'module' => $module->get_name(),
							'type'   => $type,
							'id'     => $id,
							'access' => $access
						)),
						'size'    => 'col-md-12 col-lg-5'
					))
				)
			),
			new Button_back()
		);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/access/controllers/admin.php
*/