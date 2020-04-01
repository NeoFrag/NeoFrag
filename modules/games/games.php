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
			'title'       => $this->lang('Jeux / Cartes'),
			'description' => '',
			'icon'        => 'fas fa-gamepad',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
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

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Jeux',
						'icon'   => 'fas fa-gamepad',
						'access' => [
							'add_games' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_games' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_games' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Cartes',
						'icon'   => 'far fa-map',
						'access' => [
							'add_games_maps' => [
								'title' => 'Ajouter une carte',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_games_maps' => [
								'title' => 'Modifier une carte',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_games_maps' => [
								'title' => 'Supprimer une carte',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}
}
