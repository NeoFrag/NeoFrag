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
			'icon'        => 'fa-puzzle-piece',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				//Modules
				'admin/module/{url_title}'        => '_module_settings',
				'admin/delete/module/{url_title}' => '_module_delete',

				//Thèmes
				'admin/theme/{url_title}'         => '_theme_settings',
				'admin/delete/theme/{url_title}'  => '_theme_delete',
				'admin/ajax/theme/active'         => '_theme_activation',
				'admin/ajax/theme/reset'          => '_theme_reset',
				'admin/ajax/theme/{url_title}'    => '_theme_settings',

				//Languages
				'admin/ajax/language/sort'        => '_language_sort',

				//Authenticators
				'admin/ajax/authenticator/sort'   => '_authenticator_sort',
				'admin/ajax/authenticator/admin'  => '_authenticator_admin',
				'admin/ajax/authenticator/update' => '_authenticator_update'
			]
		];
	}
}
