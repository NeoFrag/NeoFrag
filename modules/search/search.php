<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Search;

use NF\NeoFrag\Addons\Module;

class Search extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Rechercher'),
			'description' => '',
			'icon'        => 'fas fa-search',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'routes'      => [
				'(?:(.+?){pages})?' => 'index'
			]
		];
	}
}
