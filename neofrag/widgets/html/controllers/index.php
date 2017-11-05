<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_html_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->panel()->body(bbcode($settings['content']));
	}

	public function html($settings = [])
	{
		return $this->panel()->body($settings['content']);
	}
}
