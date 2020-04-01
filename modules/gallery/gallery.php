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
			'icon'        => 'far fa-image',
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
				//Admin
				'admin{pages}'                             => 'index',
				'admin/{id}/{url_title}'                   => '_edit',
				'admin/categories/add'                     => '_categories_add',
				'admin/categories/{id}/{url_title}'        => '_categories_edit',
				'admin/categories/delete/{id}/{url_title}' => '_categories_delete',
				'admin/ajax/image/add/{id}/{url_title}'    => '_image_add',
				'admin/image/{id}/{url_title}'             => '_image_edit',
				'admin/image/delete/{id}/{url_title}'      => '_image_delete'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_number('images_per_page')
										->title('Images par page')
										->value($this->config->images_per_page)
							)
							->success(function($data){
								$this->config('images_per_page', $data['images_per_page']);
								notify('Configuration modifiée');
								refresh();
							});
			}
		];
	}

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => $this->lang('Albums photos'),
						'icon'   => 'far fa-image',
						'access' => [
							'add_gallery' => [
								'title' => 'Créer',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_gallery' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_gallery' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => $this->lang('Catégories'),
						'icon'   => 'fas fa-align-left',
						'access' => [
							'add_gallery_category' => [
								'title' => 'Ajouter une catégorie',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_gallery_category' => [
								'title' => 'Modifier une catégorie',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_gallery_category' => [
								'title' => 'Supprimer une catégorie',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			],
			'gallery' => [
				'get_all' => function(){
					return NeoFrag()->db->select('gallery_id', 'title')->from('nf_gallery_lang')->where('lang', $this->config->lang->info()->name)->get();
				},
				'check'   => function($gallery_id){
					if (($gallery = NeoFrag()->db->select('title')->from('nf_gallery_lang')->where('gallery_id', $gallery_id)->where('lang', $this->config->lang->info()->name)->row()) !== [])
					{
						return $gallery;
					}
				},
				'init'    => [
					'gallery_see'     => [
					],
					'gallery_post'    => [
						['admins', TRUE]
					]
				],
				'access'  => [
					[
						'title'  => $this->lang('Galeries'),
						'icon'   => 'far fa-image',
						'access' => [
							'gallery_see' => [
								'title' => $this->lang('Voir l\'album'),
								'icon'  => 'far fa-eye'
							],
							'gallery_post' => [
								'title' => $this->lang('Poster une photo'),
								'icon'  => 'fas fa-pencil-alt'
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
