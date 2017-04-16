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
			'icon'        => 'fa-trophy',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
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
