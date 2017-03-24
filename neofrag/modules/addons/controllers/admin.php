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

class m_addons_c_admin extends Controller_Module
{
	public function index()
	{
		$this	->title($this->lang('addons'))
				->icon('fa-puzzle-piece')
				->css('addons')
				->js('addons')
				->css('neofrag.delete')
				->js('neofrag.delete')
				->css('neofrag.table')
				->js('neofrag.table')
				->js('neofrag.sortable');
		
		return $this->row(
			$this	->col(
						$this	->panel()
								->heading('<div class="pull-right"><small>(max. '.(file_upload_max_size() / 1024 / 1024).' Mo)</small></div>Ajouter un composant', 'fa-plus')
								->body('<input type="file" id="install-input" class="install" accept=".zip" /><label for="install-input" id="install-input-label"><p>'.icon('fa-upload fa-3x').'</p><span class="legend">Choisissez votre archive</span><br /><small class="text-muted">(format .zip)</small></label>')
								->footer($this	->button()
												->title('Installer')
												->icon('fa-plus')
												->color('info btn-block install disabled')
												->outline()),
						$this	->panel()
								->heading('Composants du site', 'fa-puzzle-piece')
								->body($this->view('addons', [
									'addons' => [
										'modules' => [
											'title' => 'Modules',
											'icon'  => 'fa-edit'
										],
										'themes' => [
											'title' => 'Thèmes',
											'icon'  => 'fa-tint'
										],
										'widgets' => [
											'title' => 'Widgets',
											'icon'  => 'fa-cubes'
										],
										'languages' => [
											'title' => 'Langues',
											'icon'  => 'fa-book'
										],
										'authenticators' => [
											'title' => 'Authentificateurs',
											'icon'  => 'fa-user-circle'
										]/*,
										'smileys' => array(
											'title' => 'Smileys',
											'icon'  => 'fa-smile-o'
										),
										'bbcodes' => array(
											'title' => 'BBcodes',
											'icon'  => 'fa-code'
										)*/
									]
								]), FALSE)
					)
					->size('col-md-4 col-lg-3')
		);
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

/*
NeoFrag Alpha 0.1.6
./neofrag/modules/addons/controllers/admin.php
*/