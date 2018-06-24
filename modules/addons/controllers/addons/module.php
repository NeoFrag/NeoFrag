<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers\Addons;

use NF\NeoFrag\Loadables\Controller;

class Module extends Controller
{
	public $__label = ['Modules', 'Module', 'fa-sticky-note-o', 'primary'];

	public function __actions()
	{
		return $this->array
					->set('enable', ['Activer', 'fa-check', 'success', TRUE, function($addon){
						return $addon->is_deactivatable() && !$addon->is_enabled();
					}])
					->set('disable', ['Désactiver', 'fa-times', 'muted', TRUE, function($addon){
						return $addon->is_deactivatable() && $addon->is_enabled();
					}])
					->set('settings', ['Configuration', 'fa-wrench', 'warning', TRUE, function($addon){
						return isset($addon->info()->settings);
					}])
					->set('access', ['Permissions', 'fa-unlock-alt', 'success', FALSE, function($addon){
						return $addon->get_permissions('default');
					}]);
	}

	public function enable($addon)
	{
		$addon->__addon->set('data', $addon->__addon->data->set('enabled', TRUE))->update();

		notify($this->lang('<b>%s</b> activé', $addon->info()->title));

		refresh();
	}

	public function disable($addon)
	{
		$addon->__addon->set('data', $addon->__addon->data->set('enabled', FALSE))->update();

		notify($this->lang('<b>%s</b> désactivé', $addon->info()->title));

		refresh();
	}

	public function settings($addon)
	{
		return call_user_func($addon->info()->settings)	->modal($addon->info()->title, 'fa-wrench')
														->cancel();
	}

	public function access($addon)
	{
		redirect('admin/access/edit/'.$addon->info()->name);
	}
}
