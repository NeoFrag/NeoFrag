<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Navigation\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Checker extends Controller
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
			'links' => $links
		];
	}

	public function vertical($settings = [])
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
			'links' => $links
		];
	}
}
