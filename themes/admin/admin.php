<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Themes\Admin;

use NF\NeoFrag\Addons\Theme;

class Admin extends Theme
{
	public $data;

	protected function __info()
	{
		return [
			'title'       => 'Administration',
			'description' => 'Panel d\'administration',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'zones'       => [$this->lang('Contenu'), $this->lang('pre_content'), $this->lang('post_content'), $this->lang('header'), $this->lang('Haut'), $this->lang('footer')]
		];
	}

	public function __init()
	{
		if ($update_callback = $this->config->nf_update_callback)
		{
			$this->config('nf_update_callback', '');

			foreach (unserialize($update_callback) as $patch)
			{
				NeoFrag()->install($patch)->post();
			}

			refresh();
		}

		$this	->css('bootstrap.min')
				->css('fonts/open-sans')
				->css('fonts/titillium-web')
				->css('icons/Pe-icon-7-stroke')
				->css('icons/font-awesome.min')
				->css('style')
				->js('jquery-3.2.1.min')
				->js('popper.min')
				->js('bootstrap.min')
				->js('bootstrap-notify.min')
				->js('modal')
				->js('notify');

		$this->data = $this->array;

		$content_submenu = [
			'default' => [],
			'gaming'  => []
		];

		foreach (NeoFrag()->model2('addon')->get('module') as $module)
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

		$this->data->set('sidebar', [
			'panel' => FALSE,
			'links' => [
				[
					'title' => 'Tableau de bord',
					'icon'  => 'fa-dashboard',
					'url'   => 'admin'
				],
				[
					'title' => 'Paramètres',
					'icon'  => 'fa-cogs',
					'url'   => 'admin/settings'
				],
				[
					'title' => 'Thèmes & Addons',
					'icon'  => 'fa-puzzle-piece',
					'url'   => 'admin/addons'
				],
				[
					'title' => 'Utilisateurs',
					'icon'  => 'fa-users',
					'url'   => [
						['title' => 'Membres / Groupes',      'icon'  => 'fa-users',        'access' => $this->user->admin, 'url' => 'admin/user'],
						['title' => 'Sessions',               'icon'  => 'fa-globe',        'access' => $this->user->admin, 'url' => 'admin/user/sessions'],
						['title' => 'Permissions',            'icon'  => 'fa-unlock-alt',   'access' => $this->user->admin, 'url' => 'admin/access'],
						//['title' => 'Bannissement',           'icon'  => 'fa-bomb',         'access' => $this->user->admin, 'url' => 'admin/user/ban']
					]
				],
				[
					'title' => 'Contenu',
					'icon'  => 'fa-edit',
					'url'   => $content_submenu['default']
				],
				[
					'title' => 'Gaming',
					'icon'  => 'fa-gamepad',
					'url'   => $content_submenu['gaming']
				],
				[
					'title' => 'Live Editor',
					'icon'  => 'fa-desktop',
					'url'   => 'admin/live-editor'
				],
				[
					'title'  => 'Monitoring'.$this->module('monitoring')->display(),
					'icon'   => 'fa-heartbeat',
					'access' => $this->user->admin,
					'url'    => 'admin/monitoring'
				],
				[
					'title' => 'Statistiques',
					'icon'  => 'fa-bar-chart',
					'access' => $this->user->admin,
					'url'    => 'admin/statistics'
				]
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

	public function update()
	{
		if (file_exists($file = 'cache/monitoring/version.json'))
		{
			$version = json_decode(file_get_contents($file))->neofrag;

			if (version_compare(version_format($version->version), version_format(NEOFRAG_VERSION), '>'))
			{
				return $version;
			}
		}
	}
}
