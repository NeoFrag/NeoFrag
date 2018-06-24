<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($objects, $modules, $tab)
	{
		if (!$modules)
		{
			return $this->panel()
						->heading($this->lang('Permissions'), 'fa-unlock-alt')
						->body($this->lang('Il n\'y a aucune permission à administrer'));
		}

		$this->js('access');

		foreach ($modules as $module_name => $module)
		{
			list($module, $icon, $type, $all_access) = $module;

			$title = $module->info()->title;

			$this->tab->add_tab($module_name, icon($icon).' '.$module->info()->title, function() use ($objects, $title, $module, $type, $all_access){
				$this	->subtitle($title)
						->table()
						->add_columns([
							[
								'title'   => $this->lang('Nom'),
								'content' => function($data){
									return $data['title'];
								}
							]
						]);

				foreach ($all_access['access'] as $a)
				{
					foreach ($a['access'] as $action => $access)
					{
						$this	->table()
								->add_columns([
									[
										'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$access['title'].'">'.icon($access['icon']).'</div>',
										'content' => function($data) use ($module, $action){
											return $this->access->count($module->info()->name, $action, $data['id']);
										},
										'class'   => 'col-1'
									]
								]);
					}
				}

				return $this->table()
							->add_columns([
								[
									'content' => [
										function($data) use ($module, $type){
											return $this->button()->tooltip($this->lang('Réinitialiser'))->icon('fa-refresh')->color('info access-reset')->compact()->outline()->data([
												'module' => $module->info()->name,
												'type'   => $type,
												'id'     => $data['id']
											]);
										},
										function($data) use ($module, $type){
											return $this->button_access($data['id'], $type, $module->info()->name, $this->lang('Éditer'));
										}
									]
								]
							])
							->data($objects)
							->display();
			});
		}

		return $this->panel()->body($this->tab->display($tab));
	}

	public function _edit($module, $type, $access, $id, $title = NULL)
	{
		$this	->title($module->info()->title)
				->subtitle($title ?: $this->lang('Gestion des permissions'))
				->icon($module->info()->icon)
				->css('access')
				->js('access')
				->css('table')
				->js('table');

		return [
			$this->row(
				$this->col(
					$this	->panel()
							->heading($this->lang('Liste des permissions').'<div class="pull-right">'.$this->button()->tooltip($this->lang('Réinitialiser toutes les permissions'))->icon('fa-refresh')->color('info access-reset')->compact()->outline()->data([
								'module' => $module->info()->name,
								'type'   => $type,
								'id'     => $id
							]).'</div>', 'fa-unlock-alt')
							->body($this->view('index', [
								'loader' => $module,
								'module' => $module->info()->name,
								'type'   => $type,
								'id'     => $id,
								'access' => $access
							]))
							->size('col-12 col-lg-5')
				)
			),
			$this->panel_back()
		];
	}
}
