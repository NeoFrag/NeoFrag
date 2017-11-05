<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_user_c_checker extends Controller_Module
{
	public function index_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['navbar-left', 'navbar-right']) ? $settings['align'] : 'navbar-right'
		];
	}
}
