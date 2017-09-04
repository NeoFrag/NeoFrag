<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Admin extends Controller_Widget
{
	public function index_mini($settings = [])
	{
		return $this->view('admin_mini', $settings);
	}
}
