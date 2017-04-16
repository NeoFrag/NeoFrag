<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Navigation;

use NF\NeoFrag\Addons\Widget;

class Navigation extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('navigation'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>'
		];
	}
}
