<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_header_c_admin extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->view('admin', $settings);
	}
}
