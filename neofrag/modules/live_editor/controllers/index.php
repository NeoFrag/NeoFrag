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

class m_live_editor_c_index extends Controller_Module
{
	public function index()
	{
		$this	->css('font.open-sans.300.400.600.700.800')
				->css('live-editor')
				->js('live-editor');

		$dispositions = $this->db	->select('DISTINCT page')
									->from('nf_settings_dispositions')
									->where('theme', $theme)
									->get();
		
		$modules = [];
		
		foreach ($this->addons->get_modules() as $module)
		{
			if ($module->load->controller('index') && !in_array($module->name, ['live_editor', 'pages']))
			{
				$modules[$module->name] = $module->get_title();
			}
		}
		
		natcasesort($modules);
		
		$modules = array_merge([
			'index' => NeoFrag::loader()->lang('home')
		], $modules);

		echo $this->load->view('index', [
			'modules'       => $modules,
			'styles_row'    => NeoFrag::loader()->theme->styles_row(),
			'styles_widget' => NeoFrag::loader()->theme->styles_widget()
		]);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/live_editor/controllers/index.php
*/