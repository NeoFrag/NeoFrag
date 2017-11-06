<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->css('fonts/open-sans')
				->css('live-editor')
				->js('live-editor')
				->css('jquery-ui.min')
				->js('jquery-ui.min');

		$modules = [];

		foreach (NeoFrag()->model2('addon')->get('module') as $module)
		{
			if (@$module->controller('index') && !in_array($module->name, ['live_editor', 'pages']))
			{
				$modules[$module->info()->name] = $module->info()->title;
			}
		}

		array_natsort($modules);

		$modules = array_merge([
			'index' => NeoFrag()->lang('Accueil')
		], $modules);

		$theme = $this->theme($this->config->nf_default_theme);

		return $this->view('index', [
			'modules'       => $modules,
			'styles_row'    => $theme->styles_row(),
			'styles_widget' => $theme->styles_widget()
		]);
	}
}
