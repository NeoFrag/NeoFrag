<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Breadcrumb\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		$count = count($links = NeoFrag()->module->breadcrumb->get_links());

		array_walk($links, function(&$value, $key) use ($count){
			$value = '<li'.(($is_last = $key == $count - 1) ? ' class="active"' : '').'><a href="'.url($value[1]).'">'.($is_last && $value[2] !== '' ? icon($value[2]).' ' : '').$value[0].'</a></li>';
		});

		return '<ol class="breadcrumb"><li><b>'.$this->config->nf_name.'</b></li>'.implode($links).'</ol>';
	}
}
