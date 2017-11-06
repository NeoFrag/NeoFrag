<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\User\Controllers;

class Checker extends Controller_Module
{
	public function index_mini($settings = [])
	{
		return [
			'align' => !empty($settings['align']) && in_array($settings['align'], ['navbar-left', 'navbar-right']) ? $settings['align'] : 'navbar-right'
		];
	}
}
