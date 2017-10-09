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

		foreach ($this->model2('addon')->get('module') as $module)
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

		return $this->view('index', [
			'modules'       => $modules,
			'styles_row'    => '',//$this->output->theme()->styles_row(),//TODO 0.1.7
			'styles_widget' => ''//$this->output->theme()->styles_widget()
		]);
	}
}
