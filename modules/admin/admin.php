<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Admin;

use NF\NeoFrag\Addons\Module;

class Admin extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('administration'),
			'description' => '',
			'icon'        => 'fa-dashboard',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE
		];
	}
}
