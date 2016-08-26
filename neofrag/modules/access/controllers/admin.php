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

class m_access_c_admin extends Controller_Module
{
	public $administrable = FALSE;

	public function index($objects, $modules, $tab)
	{
		if (!$modules)
		{
			return new Panel([
				'title'   => $this('permissions'),
				'icon'    => 'fa-unlock-alt',
				'content' => $this('no_permission')
			]);
		}
		
		$this->js('access');

		foreach ($modules as $module_name => $module)
		{
			list($module, $icon, $type, $access) = $module;
			$this->tab->add_tab($module_name, icon($icon).' '.$module->get_title(), '_tab_index', $objects, $module->get_title(), $module, $type, $access);
		}

		return new Panel([
			'content' => $this->tab->display($tab)
		]);
	}
	
	public function _tab_index($objects, $title, $module, $type, $all_access)
	{
		$this	->subtitle($title)
				->table
				->add_columns([
					[
						'title'   => $this('name'),
						'content' => function($data){
							return $data['title'];
						}
					]
				]);

		foreach ($all_access['access'] as $a)
		{
			foreach ($a['access'] as $action => $access)
			{
				$this	->table
						->add_columns([
							[
								'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$module->load->lang($access['title'], NULL).'">'.icon($access['icon']).'</div>',
								'content' => function($data) use ($module, $action){
									return NeoFrag::loader()->access->count($module->name, $action, $data['id']);
								},
								'class'   => 'col-md-1'
							]
						]);
			}
		}
		
		$this	->table
				->add_columns([
					[
						'content' => [
							function($data, $loader) use ($module, $type){
								return button(NULL, 'fa-refresh', $loader->lang('reset'), 'info access-reset', [
									'module' => $module->name,
									'type'   => $type,
									'id'     => $data['id']
								]);
							},
							/*function(){
								return button('#', 'fa-copy', 'Glissez pour copier', 'primary');
							},*/
							function($data, $loader) use ($module, $type){
								return button_access($data['id'], $type, $module->name, $loader->lang('edit'));
							}
						]
					]
				])
				->data($objects);

		echo $this->table->display();
	}
	
	public function _edit($module, $type, $access, $id, $title = NULL)
	{
		$this	->title($module->get_title())
				->subtitle($title ?: $this('permissions_management'))
				->icon($module->icon)
				->css('access')
				->js('access')
				->css('neofrag.table')
				->js('neofrag.table');
		
		return [
			new Row(
				new Col(
					new Panel([
						'title'   => $this('permissions_list').'<div class="pull-right">'.button(NULL, 'fa-refresh', $this('reset_all_permissions'), 'info access-reset', [
							'module' => $module->name,
							'type'   => $type,
							'id'     => $id
						]).'</div>',
						'icon'    => 'fa-unlock-alt',
						'content' => $this->load->view('index', [
							'loader' => $module->load,
							'module' => $module->name,
							'type'   => $type,
							'id'     => $id,
							'access' => $access
						]),
						'size'    => 'col-md-12 col-lg-5'
					])
				)
			),
			new Button_back()
		];
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/access/controllers/admin.php
*/