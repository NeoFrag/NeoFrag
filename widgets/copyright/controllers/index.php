<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Copyright\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		$keywords = [
			'name'      => '<a href="'.url().'">'.$this->config->nf_name.'</a>',
			'neofrag'   => '<a href="https://neofr.ag">NeoFrag</a>',
			'year'      => date('Y'),
			'copyright' => icon('far fa-copyright')
		];

		if (!in_string('{neofrag}', $copyright = utf8_html_entity_decode($this->config->nf_copyright)))
		{
			$copyright .= '<div class="float-right">'.$this->lang('Propulsé par %s', '{neofrag}').'</div>';
		}

		return $this->panel()
					->body(preg_replace_callback('/\{('.implode('|', array_keys($keywords)).')\}/i', function($match) use ($keywords){
						return $keywords[$match[1]];
					}, $copyright));
	}
}
