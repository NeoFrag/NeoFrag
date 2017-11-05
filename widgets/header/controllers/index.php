<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_header_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		echo $this->view('index', $settings);
	}
}
