<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$addons = array_filter($this->collection('addon')->get(), function($a){
			return $a->addon()->__actions();
		});

		$types = array_count_values(array_map(function($a){
			return $a->type->id;
		}, $addons));

		usort($addons, function($a, $b) use ($types){
			if ($types[$a->type->id] > $types[$b->type->id])
			{
				return 1;
			}
			else if ($types[$a->type->id] < $types[$b->type->id])
			{
				return -1;
			}
			else
			{
				return str_nat($a, $b, function($a){
					return $a->type->name.$a->addon()->title();
				});
			}
		});

		return $this->js('mixitup.min')
					->css('addons')
					//->js_load('mixitup($("#addons")[0]);')
					->view('admin', [
						'addons' => $addons
					]);
	}

	public function _module_settings($module)
	{
		$this	->title($module->get_title())
				->subtitle('Configuration')
				->icon('fa-wrench');

		return $module->settings();
	}

	public function _module_delete($module)
	{
		$this	->title('Confirmation de suppression')
				->subtitle($module->get_title())
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le module <b>'.$module->get_title().'</b> ?');

		if ($this->form->is_valid())
		{
			$module->uninstall();
			return 'OK';
		}

		echo $this->form->display();
	}

	public function _theme_settings($theme, $controller)
	{
		$this	->title($theme->get_title())
				->subtitle($this->lang('theme_customize'))
				->icon('fa-paint-brush');

		return $controller->index($theme);
	}

	public function _theme_delete($theme)
	{
		$this	->title('Confirmation de suppression')
				->subtitle($theme->get_title())
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le thème <b>'.$theme->get_title().'</b> ?');

		if ($this->form->is_valid())
		{
			$theme->uninstall();
			return 'OK';
		}

		echo $this->form->display();
	}
}
