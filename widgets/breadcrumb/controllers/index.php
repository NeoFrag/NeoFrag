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
		$count = count($links = $this->output->data->get('breadcrumb') ?: []);

		if (empty($links) && $this->url->segments[0] == 'index')
		{
			array_unshift($links, [$this->lang('home'), '', 'fa-map-marker']);
		}
		else if ($this->output->module())
		{
			array_unshift($links, [$this->output->module()->get_title(), $this->output->module()->info()->name == 'pages' ? $this->url->request : $this->output->module()->info()->name, $this->output->module()->info()->icon ?: 'fa-map-marker']);
		}

		array_walk($links, function(&$value, $key) use ($count){
			$value = '<li'.(($is_last = $key == $count - 1) ? ' class="active"' : '').'><a href="'.url($value[1]).'">'.($is_last && $value[2] !== '' ? icon($value[2]).' ' : '').$value[0].'</a></li>';
		});

		return '<ol class="breadcrumb"><li><b>'.$this->config->nf_name.'</b></li>'.implode($links).'</ol>';
	}
}
