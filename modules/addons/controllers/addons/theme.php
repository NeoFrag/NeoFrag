<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers\Addons;

use NF\NeoFrag\Loadables\Controller;

class Theme extends Controller
{
	public $__label = ['Thèmes', 'Thème', 'fas fa-tint', 'success'];

	public function __actions()
	{
		return $this->array
					->set('enable',    ['Activer', 'fas fa-check', 'success', TRUE, function($addon){
						return $addon->info()->name != 'admin' && !$addon->is_enabled();
					}])
					->set('customize', ['Personaliser', 'fas fa-paint-brush', 'info', FALSE, function($addon){
						return $addon->info()->name != 'admin' && @$addon->controller('admin');
					}])
					->set('reset',     ['Réinstaller par défaut', 'fas fa-sync', 'warning', TRUE, function($addon){
						return $addon->info()->name != 'admin';
					}]);
	}

	public function enable($addon)
	{
		$this->config('nf_default_theme', $addon->info()->name);

		notify($this->lang('<b>%s</b> activé', $addon->info()->title));

		refresh();
	}

	public function customize($theme, $controller)
	{
		$controller	->title($theme->info()->title)
					->subtitle('Personnalisation du thème')
					->icon('fas fa-paint-brush')
					->add_action($this->button('Réinstaller par défaut', 'fas fa-sync', 'warning')->modal($this->reset($theme)));

		return $theme->controller('admin')->index();
	}

	public function reset($theme)
	{
		return $this->modal('Réinstaller par défaut', 'fas fa-sync')
					->body($this->lang('Êtes-vous sûr(e) de vouloir réinstaller le thème <b>%s</b> ?<br />Toutes les dispositions et configurations de widgets seront perdues.', $theme->info()->title))
					->submit('Réinstaller', 'warning')
					->cancel()
					->callback(function() use ($theme){
						$theme->reset();
						notify($this->lang('Thème %s réinstallé par défaut', $theme->info()->title));
						refresh();
					});
	}
}
