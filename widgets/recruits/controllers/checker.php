<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Recruits\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Checker extends Controller
{
	public function recruit($settings = [])
	{
		if (in_array($settings['recruit_id'], array_map(function($a){
			return $a['recruit_id'];
		}, $this->model()->get_recruits())))
		{
			return [
				'recruit_id' => $settings['recruit_id']
			];
		}
	}
}
