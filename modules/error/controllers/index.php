<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_error_c_index extends Controller_Module
{
	public function index()
	{
		header('HTTP/1.0 404 Not Found');

		$this->title($this->lang('Page introuvable'));

		return [
			$this	->panel()
					->heading($this->lang('Page introuvable'), 'fa-warning')
					->body($this->lang('La page que vous souhaitez consulter est introuvable'))
					->color('danger'),
			$this->panel_back()
		];
	}

	public function unauthorized()
	{
		header('HTTP/1.0 401 Unauthorized');

		$this->title($this->lang('Accès refusé'));

		return [
			$this	->panel()
					->heading($this->lang('Accès refusé'), 'fa-warning')
					->body($this->lang('Vous n\'avez pas les autorisations d\'accès requises pour visiter cette page'))
					->color('danger'),
			$this->panel_back()
		];
	}
}
