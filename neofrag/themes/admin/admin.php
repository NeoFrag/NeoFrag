<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class t_admin extends Theme
{
	public $title       = '{lang administration}';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;

	public function load()
	{
		$content_submenu = [
			'default' => [],
			'gaming'  => []
		];

		foreach ($this->addons->get_modules() as $module)
		{
			if ($module->is_administrable($category) && $category != 'none' && $module->is_authorized())
			{
				$content_submenu[isset($content_submenu[$category]) ? $category : 'default'][] = [
					'title'  => $module->get_title(),
					'icon'   => $module->icon,
					'url'    => 'admin/'.$module->name
				];
			}
		}

		array_walk($content_submenu, function(&$a){
			array_natsort($a, function($a){
				return $a['title'];
			});
		});

		if (file_exists($file = 'cache/monitoring/version.json'))
		{
			$version = json_decode(file_get_contents($file))->neofrag;
			
			if (version_compare(version_format($version->version), version_format(NEOFRAG_VERSION), '>'))
			{
				$this->add_data('update', $version);
				$this->js('neofrag.update');
			}
		}

		$this	->css('font.open-sans.300.400.600.700.800')
				->css('font.roboto.100.300.400.500.700.900')
				->css('font.signika-negative.400.600')
				->css('sb-admin-2')
				->css('font-awesome.min')
				->css('style')
				->js('metisMenu.min')
				->js('neofrag.navigation')
				->js('slideout.min')
				->add_data('menu', [
					[
						'title' => $this->lang('dashboard'),
						'icon'  => 'fa-dashboard',
						'url'   => 'admin'
					],
					[
						'title' => $this->lang('settings'),
						'icon'  => 'fa-cogs',
						'url'   => [
							[
								'title'  => $this->lang('configuration'),
								'icon'   => 'fa-wrench',
								'url'    => 'admin/settings',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('maintenance'),
								'icon'   => 'fa-power-off',
								'url'    => 'admin/settings/maintenance',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('addons'),
								'icon'   => 'fa-puzzle-piece',
								'url'    => 'admin/addons',
								'access' => $this->user('admin')
							]
						]
					],
					[
						'title' => $this->lang('users'),
						'icon'  => 'fa-users',
						'url'   => [
							[
								'title'  => 'Membres / Groupes',
								'icon'   => 'fa-users',
								'url'    => 'admin/user',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('sessions'),
								'icon'   => 'fa-globe',
								'url'    => 'admin/user/sessions',
								'access' => $this->user('admin')
							],
							/*array(
								'title' => 'Profil',
								'icon'  => 'fa-user',
								'url'   => 'admin/user'
							),*/
							[
								'title'  => $this->lang('permissions'),
								'icon'   => 'fa-unlock-alt',
								'url'    => 'admin/access',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('ban'),
								'icon'   => 'fa-bomb',
								'url'    => 'admin/user/ban',
								'access' => $this->user('admin')
							]
						]
					],
					[
						'title' => $this->lang('content'),
						'icon'  => 'fa-edit',
						'url'   => $content_submenu['default']
					],
					[
						'title' => 'Gaming',
						'icon'  => 'fa-gamepad',
						'url'   => $content_submenu['gaming']
					],
					[
						'title' => $this->lang('design'),
						'icon'  => 'fa-paint-brush',
						'url'   => [
							[
								'title'  => $this->lang('themes'),
								'icon'   => 'fa-tint',
								'url'    => 'admin/addons#themes',
								'access' => $this->user('admin')
							],
							[
								'title' => $this->lang('liveditor'),
								'icon'  => 'fa-desktop',
								'url'   => 'live-editor',
								'access' => $this->user('admin')
							]
						]
					],
					[
						'title'  => 'Monitoring'.$this->module('monitoring')->display(),
						'icon'   => 'fa-heartbeat',
						'url'    => 'admin/monitoring',
						'access' => $this->user('admin')
					],
					[
						'title'  => 'Statistiques',
						'icon'   => 'fa-bar-chart',
						'url'    => 'admin/statistics',
						'access' => $this->user('admin')
					],
					[
						'title'  => $this->lang('about'),
						'icon'   => 'fa-info',
						'url'    => 'admin/about',
						'access' => $this->user('admin')
					]
				]);

		return parent::load();
	}
	
	public function styles_row()
	{
		//Nothing to do
	}
	
	public function styles_widget()
	{
		//Nothing to do
	}
}
