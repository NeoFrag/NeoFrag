<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages;

use NF\NeoFrag\Addons\Module;

class Pages extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Pages'),
			'description' => '',
			'icon'        => 'fa-file-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'routes'      => [
				//Index
				'{url_title}'             => '_index',

				//Admin
				'admin{pages}'            => 'index',
				'admin/{id}/{url_title*}' => '_edit'
			]
		];
	}

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Pages',
						'icon'   => 'fa-file-o',
						'access' => [
							'add_pages' => [
								'title' => 'Ajouter',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_pages' => [
								'title' => 'Modifier',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_pages' => [
								'title' => 'Supprimer',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}
}
