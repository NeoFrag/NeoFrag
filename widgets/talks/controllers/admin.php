<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Talks\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Admin extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->view('admin', [
			'talks'    => $this->db->select('talk_id', 'name')->from('nf_talks')->get(),
			'settings' => $settings
		]);
	}
}
