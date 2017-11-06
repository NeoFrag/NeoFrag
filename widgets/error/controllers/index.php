<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class W_Error_C_Index extends Controller_Widget
{
	public function index($config = [])
	{
		return $this->panel()
					->heading($this->lang('error'), 'fa-warning')
					->body($this->lang('Widget introuvable ou mal configuré'))
					->color('danger');
	}
}
