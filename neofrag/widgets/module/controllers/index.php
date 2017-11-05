<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_module_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		$this->title($this->output->data['module_title']);
		echo NeoFrag()->module;
	}
}
