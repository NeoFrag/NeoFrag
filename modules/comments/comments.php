<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments;

use NF\NeoFrag\Addons\Module;

class Comments extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('comments'),
			'description' => '',
			'icon'        => 'fa-comments-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'routes'      => [
				'admin/([a-z0-9-]*?){pages}' => 'index'
			]
		];
	}
}
