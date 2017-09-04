<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Navigation\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		if ($settings['display'])
		{
			return $this->view('index', $settings);
		}
		else
		{
			return $this->panel()->body($this->view('horizontal', $settings), FALSE);
		}
	}
}
