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

		$this->title($this->lang('unfound'));

		return [
			$this	->panel()
					->heading($this->lang('unfound'), 'fa-warning')
					->body($this->lang('page_unfound'))
					->color('danger'),
			$this->panel_back()
		];
	}

	public function unauthorized()
	{
		header('HTTP/1.0 401 Unauthorized');

		$this->title($this->lang('unauthorized'));

		return [
			$this	->panel()
					->heading($this->lang('unauthorized'), 'fa-warning')
					->body($this->lang('required_permissions'))
					->color('danger'),
			$this->panel_back()
		];
	}
}
