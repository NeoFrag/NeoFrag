<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\About\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Checker extends Controller
{
	public function index($settings = [])
	{
		return [
			'display_panel'      => in_array($settings['display_panel'], ['oui', 'non']) ? $settings['display_panel'] : 'oui',
			'display_teamname'   => in_array($settings['display_teamname'], ['oui', 'non']) ? $settings['display_teamname'] : 'oui',
			'display_logo'       => in_array($settings['display_logo'], ['oui', 'non']) ? $settings['display_logo'] : 'oui',
			'display_type'       => in_array($settings['display_type'], ['oui', 'non']) ? $settings['display_type'] : 'oui',
			'display_date'       => in_array($settings['display_date'], ['oui', 'non']) ? $settings['display_date'] : 'oui',
			'display_biographie' => in_array($settings['display_biographie'], ['oui', 'non']) ? $settings['display_biographie'] : 'oui',
			'biographie_align'   => in_array($settings['biographie_align'], ['text-left', 'text-center', 'text-right']) ? $settings['biographie_align'] : 'text-left',
			'teamname_align'     => in_array($settings['teamname_align'], ['text-left', 'text-center', 'text-right']) ? $settings['teamname_align'] : 'text-left',
			'logo_align'         => in_array($settings['logo_align'], ['text-left', 'text-center', 'text-right']) ? $settings['logo_align'] : 'text-left',
			'logo_width'         => $settings['logo_width'] ? $settings['logo_width'] : '200',
			'padding_top'        => $settings['padding_top'] ? $settings['padding_top'] : '0',
			'padding_right'      => $settings['padding_right'] ? $settings['padding_right'] : '0',
			'padding_bottom'     => $settings['padding_bottom'] ? $settings['padding_bottom'] : '0',
			'padding_left'       => $settings['padding_left'] ? $settings['padding_left'] : '0',
			'margin_top'         => $settings['margin_top'] ? $settings['margin_top'] : '0',
			'margin_right'       => $settings['margin_right'] ? $settings['margin_right'] : '0',
			'margin_bottom'      => $settings['margin_bottom'] ? $settings['margin_bottom'] : '0',
			'margin_left'        => $settings['margin_left'] ? $settings['margin_left'] : '0',
			'style_title'        => preg_match($regex = '/^#([a-f0-9]{3}){1,2}$/i', $settings['style_title']) ? $settings['style_title'] : '',
			'style_text'         => preg_match($regex,                              $settings['style_text'])  ? $settings['style_text']  : ''
		];
	}
}
