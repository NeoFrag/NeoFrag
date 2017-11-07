<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_error_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		return $this->panel()
					->heading($this->lang('error'), 'fa-warning')
					->body($this->lang('widget_error'))
					->color('danger');
	}
}
