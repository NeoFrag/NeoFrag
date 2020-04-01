<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access;

use NF\NeoFrag\Addons\Module;

class Access extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Permissions'),
			'description' => '',
			'icon'        => 'fas fa-unlock-alt',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				'admin/edit/{url_title*}'  => '_edit',
				'admin/([a-z0-9-]*?){pages}' => 'index'
			]
		];
	}
}
