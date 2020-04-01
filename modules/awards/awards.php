<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Awards;

use NF\NeoFrag\Addons\Module;

class Awards extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Palmarès',
			'description' => '',
			'icon'        => 'fas fa-trophy',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'routes'      => [
				//Index
				'{id}/{url_title}'             => '_award',
				'{url_title}/{id}/{url_title}' => '_filter',
				//Admin
				'admin{pages}'                 => 'index',
				'admin/{id}/{url_title*}'      => '_edit'
			]
		];
	}

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Palmarès',
						'icon'   => 'fas fa-trophy',
						'access' => [
							'add_awards' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_awards' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_awards' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}

	public function comments($award_id)
	{
		$award = $this->db	->select('name')
							->from('nf_awards')
							->where('award_id', $award_id)
							->row();

		if ($award)
		{
			return [
				'title' => $award,
				'url'   => 'awards/'.$award_id.'/'.url_title($award)
			];
		}
	}
}
