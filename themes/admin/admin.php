<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Themes\Admin;

use NF\NeoFrag\Addons\Theme;

class Admin extends Theme
{
	protected function __info()
	{
		return [
			'title'       => 'Administration',
			'description' => $this->lang('default_theme_description'),
			'thumbnail'   => 'themes/default/images/thumbnail.png',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'zones'       => [$this->lang('Contenu'), $this->lang('pre_content'), $this->lang('post_content'), $this->lang('header'), $this->lang('Haut'), $this->lang('footer')]
		];
	}

	public function __init()
	{
		$content_submenu = [
			'default' => [],
			'gaming'  => []
		];

		foreach ($this->model2('addon')->get('module') as $module)
		{
			if ($module->is_administrable($category) && $category != 'none' && $module->is_authorized())
			{
				$content_submenu[isset($content_submenu[$category]) ? $category : 'default'][] = [
					'title' => (string)$module->info()->title,
					'icon'  => $module->info()->icon,
					'url'   => 'admin/'.$module->info()->name
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
				$this->js('update');
			}
		}

		$this	->css('bootstrap.min')
				->css('font-awesome.min')
				->css('font-awesome-override')
				->css('notify')
				->css('font.open-sans.300.400.600.700.800')
				->css('font.roboto.100.300.400.500.700.900')
				->css('font.signika-negative.400.600')
				->css('sb-admin-2')
				->css('style')
				->js('jquery-1.11.2.min')
				->js('jquery-ui.min')
				->js('metisMenu.min')
				->js('navigation')
				->js('slideout.min')
				->js('popper.min')
				->js('bootstrap.min')
				->js('bootstrap-notify.min')
				->js('notify')
				->js('user')
				->add_data('menu', [
					[
						'title' => $this->lang('Tableau de bord'),
						'icon'  => 'fa-dashboard',
						'url'   => 'admin'
					],
					[
						'title' => $this->lang('Paramètres'),
						'icon'  => 'fa-cogs',
						'url'   => [
							[
								'title'  => $this->lang('Configuration'),
								'icon'   => 'fa-wrench',
								'url'    => 'admin/settings',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('Maintenance'),
								'icon'   => 'fa-power-off',
								'url'    => 'admin/settings/maintenance',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('Gestion des composants'),
								'icon'   => 'fa-puzzle-piece',
								'url'    => 'admin/addons',
								'access' => $this->user('admin')
							]
						]
					],
					[
						'title' => $this->lang('Utilisateurs'),
						'icon'  => 'fa-users',
						'url'   => [
							[
								'title'  => 'Membres / Groupes',
								'icon'   => 'fa-users',
								'url'    => 'admin/user',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('Sessions'),
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
								'title'  => $this->lang('Permissions'),
								'icon'   => 'fa-unlock-alt',
								'url'    => 'admin/access',
								'access' => $this->user('admin')
							],
							[
								'title'  => $this->lang('Bannissement'),
								'icon'   => 'fa-bomb',
								'url'    => 'admin/user/ban',
								'access' => $this->user('admin')
							]
						]
					],
					[
						'title' => $this->lang('Contenu'),
						'icon'  => 'fa-edit',
						'url'   => $content_submenu['default']
					],
					[
						'title' => 'Gaming',
						'icon'  => 'fa-gamepad',
						'url'   => $content_submenu['gaming']
					],
					[
						'title' => $this->lang('Apparence'),
						'icon'  => 'fa-paint-brush',
						'url'   => [
							[
								'title'  => $this->lang('Thèmes'),
								'icon'   => 'fa-tint',
								'url'    => 'admin/addons#themes',
								'access' => $this->user('admin')
							],
							[
								'title' => $this->lang('Live Editor'),
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
						'title'  => $this->lang('À propos'),
						'icon'   => 'fa-info',
						'url'    => 'admin/about',
						'access' => $this->user('admin')
					]
				]);
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
