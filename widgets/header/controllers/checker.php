<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Header\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function index($settings = [])
	{
		return [
			'align'             => in_array($settings['align'], ['text-left', 'text-right']) ? $settings['align'] : 'text-center',
			'title'             => utf8_htmlentities($settings['title']),
			'description'       => utf8_htmlentities($settings['description']),
			'color-title'       => preg_match($regex = '/^#([a-f0-9]{3}){1,2}$/i', $settings['color-title'])       ? $settings['color-title']       : '',
			'color-description' => preg_match($regex,                              $settings['color-description']) ? $settings['color-description'] : ''
		];
	}
}
