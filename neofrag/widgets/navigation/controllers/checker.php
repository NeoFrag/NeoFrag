<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_navigation_c_checker extends Controller_Widget
{
	public function index($settings = [])
	{
		$links = [];
		
		foreach ($settings as $key => $values)
		{
			if (in_array($key, ['title', 'url', 'target']))
			{
				foreach ($values as $i => $value)
				{
					$links[$i][$key] = utf8_htmlentities($value);
				}
			}
		}
		
		return [
			'links'   => $links,
			'display' => (bool)$settings['display']
		];
	}
}
