<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Recruits\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Admin extends Controller_Widget
{
	public function recruit($settings = [])
	{
		return $this->view('admin_recruits', [
			'recruit_id' => isset($settings['recruit_id']) ? $settings['recruit_id'] : 0,
			'recruits'   => $this->model()->get_recruits()
		]);
	}
}
