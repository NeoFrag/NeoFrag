<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Members\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function online_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['pull-left', 'pull-right']) ? $settings['align'] : 'pull-right'
		];
	}
}
