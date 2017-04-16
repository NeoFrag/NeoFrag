<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Games;

use NF\NeoFrag\Addons\Module;

class Games extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('games_maps'),
			'description' => '',
			'icon'        => 'fa-gamepad',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				//Admin
				'admin{pages}'                  => 'index',
				'admin/{id}/{url_title}{pages}' => '_edit',

				//Maps
				'admin/maps/add(?:/{id}/{url_title})?' => '_maps_add',
				'admin/maps/edit/{id}/{url_title}'     => '_maps_edit',
				'admin/maps/delete/{id}/{url_title}'   => '_maps_delete',

				//Modes
				'admin/modes/add/{id}/{url_title}'    => '_modes_add',
				'admin/modes/edit/{id}/{url_title}'   => '_modes_edit',
				'admin/modes/delete/{id}/{url_title}' => '_modes_delete'
			]
		];
	}
}
