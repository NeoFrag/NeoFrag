<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_recruits_c_admin extends Controller
{
	public function recruit($settings = [])
	{
		return $this->view('admin_recruits', [
			'recruit_id' => isset($settings['recruit_id']) ? $settings['recruit_id'] : 0,
			'recruits'   => $this->model()->get_recruits()
		]);
	}
}
