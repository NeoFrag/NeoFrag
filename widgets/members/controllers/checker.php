<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Members\Controllers;

class Checker extends Controller_Module
{
	public function online_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['pull-left', 'pull-right']) ? $settings['align'] : 'pull-right'
		];
	}
}
