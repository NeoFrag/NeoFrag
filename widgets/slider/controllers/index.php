<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_slider_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		echo $this->view('index');
	}
}
