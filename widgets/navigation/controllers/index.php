<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_navigation_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		if ($settings['display'])
		{
			return $this->view('index', $settings);
		}
		else
		{
			return $this->panel()->body($this->view('horizontal', $settings), FALSE);
		}
	}
}
