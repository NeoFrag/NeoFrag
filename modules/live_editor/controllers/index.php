<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index()
	{
		$this	->css('font.open-sans.300.400.600.700.800')
				->css('live-editor')
				->js('live-editor');

		$modules = [];

		foreach ($this->addons->get_modules() as $module)
		{
			if ($module->controller('index') && !in_array($module->name, ['live_editor', 'pages']))
			{
				$modules[$module->name] = $module->get_title();
			}
		}

		array_natsort($modules);

		$modules = array_merge([
			'index' => NeoFrag()->lang('home')
		], $modules);

		echo $this->view('index', [
			'modules'       => $modules,
			'styles_row'    => NeoFrag()->theme->styles_row(),
			'styles_widget' => NeoFrag()->theme->styles_widget()
		]);
	}
}
