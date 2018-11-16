<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Html\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Checker extends Controller
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
