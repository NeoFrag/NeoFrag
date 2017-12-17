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
		return $this->_display($settings, 'horizontal', !empty($settings['panel']));
	}

	public function vertical($settings = [])
	{
		return $this->_display($settings, 'vertical', !isset($settings['panel']) || $settings['panel']);
	}

	protected function _display($settings, $type, $panel)
	{
		$this->js('navigation');

		$view = $this->view($type, $settings);

		if ($panel)
		{
			$view = $this	->panel()
							->body($view, FALSE);
		}

		return $view;
	}
}
