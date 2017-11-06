<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery;

use NF\NeoFrag\Addons\Module;

class Gallery extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Galeries'),
			'description' => '',
			'icon'        => 'fa-photo',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				//Index
				'{id}/{url_title}'                         => '_category',
				'album/{id}/{url_title}{page}'             => '_gallery',
				'image/{id}/{url_title}'                   => '_image',
				//Admin
				'admin{pages}'                             => 'index',
				'admin/{id}/{url_title}'                   => '_edit',
				'admin/categories/add'                     => '_categories_add',
				'admin/categories/{id}/{url_title}'        => '_categories_edit',
				'admin/categories/delete/{id}/{url_title}' => '_categories_delete',
				'admin/ajax/image/add/{id}/{url_title}'    => '_image_add',
				'admin/image/{id}/{url_title}'             => '_image_edit',
				'admin/image/delete/{id}/{url_title}'      => '_image_delete'
			]
		];
	}

	public function comments($image_id)
	{
		$image = $this->db	->select('title')
							->from('nf_gallery_images')
							->where('image_id', $image_id)
							->row();

		if ($image)
		{
			return [
				'title' => $image,
				'url'   => 'gallery/image/'.$image_id.'/'.url_title($image)
			];
		}
	}
}
