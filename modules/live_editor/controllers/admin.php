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

		$modules = $pages = [];

		foreach (NeoFrag()->model2('addon')->get('module') as $module)
		{
			if (@$module->controller('index') && !in_array($module->info()->name, ['settings']))
			{
				$modules[] = $module;
			}
		}

		array_natsort($modules, function($a){
			return $a->info()->title;
		});

		$pages = array_merge([
			'index' => NeoFrag()->lang('Accueil')
		], $pages);

		foreach ($modules as $module)
		{
			$name = $module->info()->name;

			if ($name == 'pages')
			{
				foreach ($module->model()->get_pages() as $page)
				{
					if ($page['published'])
					{
						$pages[$page['name']] = 'Page : '.str_shortener($page['title'], 35);
					}
				}
			}
			else
			{
				$pages[$name] = $module->info()->title;
			}
		}

		$theme = $this->theme($this->config->nf_default_theme);

		return $this->view('index', [
			'modules'       => $pages,
			'styles_row'    => $theme->styles_row(),
			'styles_widget' => $theme->styles_widget()
		]);
	}
}
