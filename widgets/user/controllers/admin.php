<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Admin extends Controller
{
	public function index_mini($settings = [])
	{
		return $this->view('admin_mini', $settings);
	}
}
