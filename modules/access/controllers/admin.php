<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_access_c_admin extends Controller_Module
{
	public function index($objects, $modules, $tab)
	{
		if (!$modules)
		{
			return $this->panel()
						->heading($this->lang('permissions'), 'fa-unlock-alt')
						->body($this->lang('no_permission'));
		}

		$this->js('access');

		foreach ($modules as $module_name => $module)
		{
			list($module, $icon, $type, $all_access) = $module;

			$title = $module->get_title();

			$this->tab->add_tab($module_name, icon($icon).' '.$module->get_title(), function() use ($objects, $title, $module, $type, $all_access){
				$this	->subtitle($title)
						->table
						->add_columns([
							[
								'title'   => $this->lang('name'),
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
										'title'   => '<div class="text-center" data-toggle="tooltip" title="'.$module->lang($access['title'], NULL).'">'.icon($access['icon']).'</div>',
										'content' => function($data) use ($module, $action){
											return NeoFrag()->access->count($module->name, $action, $data['id']);
										},
										'class'   => 'col-md-1'
									]
								]);
					}
				}

				return $this->table
							->add_columns([
								[
									'content' => [
										function($data) use ($module, $type){
											return $this->button()->tooltip($this->lang('reset'))->icon('fa-refresh')->color('info access-reset')->compact()->outline()->data([
												'module' => $module->name,
												'type'   => $type,
												'id'     => $data['id']
											]);
										},
										function($data) use ($module, $type){
											return $this->button_access($data['id'], $type, $module->name, $this->lang('edit'));
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
		$this	->title($module->get_title())
				->subtitle($title ?: $this->lang('permissions_management'))
				->icon($module->icon)
				->css('access')
				->js('access')
				->css('neofrag.table')
				->js('neofrag.table');

		return [
			$this->row(
				$this->col(
					$this	->panel()
							->heading($this->lang('permissions_list').'<div class="pull-right">'.$this->button()->tooltip($this->lang('reset_all_permissions'))->icon('fa-refresh')->color('info access-reset')->compact()->outline()->data([
								'module' => $module->name,
								'type'   => $type,
								'id'     => $id
							]).'</div>', 'fa-unlock-alt')
							->body($this->view('index', [
								'loader' => $module->load,
								'module' => $module->name,
								'type'   => $type,
								'id'     => $id,
								'access' => $access
							]))
							->size('col-md-12 col-lg-5')
				)
			),
			$this->panel_back()
		];
	}
}
