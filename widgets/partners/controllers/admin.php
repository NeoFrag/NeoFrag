<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Partners\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Admin extends Controller_Widget
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
