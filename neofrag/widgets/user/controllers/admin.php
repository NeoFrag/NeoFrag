<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_user_c_admin extends Controller_Module
{
	public function index_mini($settings = [])
	{
		return $this->view('admin_mini', $settings);
	}
}
