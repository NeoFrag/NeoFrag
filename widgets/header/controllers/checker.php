<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_header_c_checker extends Controller_Widget
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
