<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Search\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Checker extends Controller
{
	public function index($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['pull-left', 'pull-right']) ? $settings['align'] : 'pull-right'
		];
	}
}
