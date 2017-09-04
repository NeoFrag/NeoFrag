<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function index_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['navbar-left', 'navbar-right']) ? $settings['align'] : 'navbar-right'
		];
	}
}
