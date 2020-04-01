<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News;

use NF\NeoFrag\Addons\Module;

class News extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Actualités'),
			'description' => '',
			'icon'        => 'far fa-file-alt',
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
				'{page}'                                   => 'index',
				'{id}/{url_title}'                         => '_news',
				'tag/{url_title}{pages}'                   => '_tag',
				'category/{id}/{url_title}{pages}'         => '_category',

				//Admin
				'admin{pages}'                             => 'index',
				'admin/{id}/{url_title}'                   => '_edit',
				'admin/categories/add'                     => '_categories_add',
				'admin/categories/{id}/{url_title}'        => '_categories_edit',
				'admin/categories/delete/{id}/{url_title}' => '_categories_delete'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_number('news_per_page')
										->title('Actualités par page')
										->value($this->config->news_per_page)
							)
							->success(function($data){
								$this->config('news_per_page', $data['news_per_page']);
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
						'title'  => 'Actualités',
						'icon'   => 'far fa-file-alt',
						'access' => [
							'add_news' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_news' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_news' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Catégories',
						'icon'   => 'fas fa-align-left',
						'access' => [
							'add_news_category' => [
								'title' => 'Ajouter une catégorie',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_news_category' => [
								'title' => 'Modifier une catégorie',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_news_category' => [
								'title' => 'Supprimer une catégorie',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}

	public function comments($news_id)
	{
		$news = $this->db	->select('title')
							->from('nf_news_lang')
							->where('news_id', $news_id)
							->where('lang', $this->config->lang->info()->name)
							->row();

		if ($news)
		{
			return [
				'title' => $news,
				'url'   => 'news/'.$news_id.'/'.url_title($news)
			];
		}
	}
}
