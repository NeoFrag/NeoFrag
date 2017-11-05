<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_partners_c_admin extends Controller
{
	public function index($settings = [])
	{
		return $this->view('admin_index', $settings);
	}

	public function column($settings = [])
	{
		return $this->view('admin_column', $settings);
	}
}
