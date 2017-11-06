<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

class Admin extends Controller_Module
{
	public function index_mini($settings = [])
	{
		return $this->view('admin_mini', $settings);
	}
}
