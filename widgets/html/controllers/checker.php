<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_html_c_checker extends Controller_Widget
{
	public function index($settings = [])
	{
		return [
			'content' => $settings['content']
		];
	}

	public function html($settings = [])
	{
		return [
			'content' => $settings['content']
		];
	}
}
