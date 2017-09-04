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
		$this	->title($this->lang('addons'))
				->icon('fa-puzzle-piece')
				->css('addons')
				->js('addons')
				->css('delete')
				->js('delete')
				->css('table')
				->js('table')
				->js('sortable');

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
