<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Statistics;

use NF\NeoFrag\Addons\Module;

class Statistics extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Statistiques',
			'description' => '',
			'icon'        => 'far fa-chart-bar',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE
		];
	}
}
