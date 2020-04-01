<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons;

use NF\NeoFrag\Addons\Module;

class Addons extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Thèmes & Addons',
			'description' => '',
			'icon'        => 'fas fa-puzzle-piece',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				'admin/{url_title}/{id}/{url_title}' => '_action'
			]
		];
	}
}
