<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_talks_c_checker extends Controller_Widget
{
	public function index($settings = [])
	{
		$talks = $this->db->select('talk_id')->from('nf_talks')->get();

		return [
			'talk_id' => in_array($settings['talk_id'], $talks) ? $settings['talk_id'] : current($talks)
		];
	}
}
