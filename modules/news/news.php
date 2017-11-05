<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_news extends Module
{
	public $title       = '{lang news}';
	public $description = '';
	public $icon        = 'fa-file-text-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
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
	];

	public static function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Actualités',
						'icon'   => 'file-text-o',
						'access' => [
							'add_news' => [
								'title' => 'Ajouter',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_news' => [
								'title' => 'Modifier',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_news' => [
								'title' => 'Supprimer',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Catégories',
						'icon'   => 'fa-align-left',
						'access' => [
							'add_news_category' => [
								'title' => 'Ajouter une catégorie',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_news_category' => [
								'title' => 'Modifier une catégorie',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_news_category' => [
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

	public function comments($news_id)
	{
		$news = $this->db	->select('title')
							->from('nf_news_lang')
							->where('news_id', $news_id)
							->where('lang', $this->config->lang)
							->row();

		if ($news)
		{
			return [
				'title' => $news,
				'url'   => 'news/'.$news_id.'/'.url_title($news)
			];
		}
	}
	
	public function settings()
	{
		$this	->form
				->add_rules([
					'news_per_page' => [
						'label' => '{lang news_per_page}',
						'value' => $this->config->news_per_page,
						'type'  => 'number',
						'rules' => 'required'
					]
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/addons#modules');

		if ($this->form->is_valid($post))
		{
			$this->config('news_per_page', $post['news_per_page']);
			
			redirect_back('admin/addons#modules');
		}

		return $this->panel()->body($this->form->display());
	}
}
