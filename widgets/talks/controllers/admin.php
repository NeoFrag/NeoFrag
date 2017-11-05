<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_talks_c_admin extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->view('admin', [
			'talks'    => $this->db->select('talk_id', 'name')->from('nf_talks')->get(),
			'settings' => $settings
		]);
	}
}
