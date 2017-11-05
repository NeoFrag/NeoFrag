<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_members_c_checker extends Controller_Module
{
	public function online_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['pull-left', 'pull-right']) ? $settings['align'] : 'pull-right'
		];
	}
}
