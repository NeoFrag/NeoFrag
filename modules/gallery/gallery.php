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
				'neofrag' => 'Alpha 0.2'
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

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Albums photos',
						'icon'   => 'fa-photo',
						'access' => [
							'add_gallery' => [
								'title' => 'Créer',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_gallery' => [
								'title' => 'Modifier',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_gallery' => [
								'title' => 'Supprimer',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							],
							'post_gallery_image' => [
								'title' => 'Poster un dans un album',
								'icon'  => 'fa-photo',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Catégories',
						'icon'   => 'fa-book',
						'access' => [
							'add_gallery_categories' => [
								'title' => 'Ajouter une catégorie',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_gallery_categories' => [
								'title' => 'Modifier une catégorie',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_gallery_categories' => [
								'title' => 'Supprimer une catégorie',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					]
				]
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
