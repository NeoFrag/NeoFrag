<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_search_c_index extends Controller_Widget
{
	public function index($config = [])
	{
		return $this->css('search')->view('index');
	}
}
