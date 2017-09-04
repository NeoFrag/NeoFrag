<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Html\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function index($settings = [])
	{
		return [
			'content' => $settings['content']
		];
	}

	public function html($settings = [])
	{
		return [
			'content' => $settings['content']
		];
	}
}
