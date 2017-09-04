<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Talks\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function index($settings = [])
	{
		$talks = $this->db->select('talk_id')->from('nf_talks')->get();

		return [
			'talk_id' => in_array($settings['talk_id'], $talks) ? $settings['talk_id'] : current($talks)
		];
	}
}
