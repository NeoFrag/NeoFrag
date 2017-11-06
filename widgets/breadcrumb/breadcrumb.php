<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Breadcrumb;

use NF\NeoFrag\Addons\Widget;

class Breadcrumb extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Fil d\'Ariane'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>'
		];
	}
}
