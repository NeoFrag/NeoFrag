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
				->css('icons/fontawesome.min')
				->css('style')
				->js('jquery-3.2.1.min')
				->js('popper.min')
				->js('bootstrap.min')
				->js('bootstrap-notify.min')
				->js('modal')
				->js('notify')
				->js('sidebar');

		$this->data = $this->array;

		$content_submenu = [
			'default' => [],
			'gaming'  => []
		];

		foreach (NeoFrag()->model2('addon')->get('module') as $module)
		{
			if ($module->is_enabled() && $module->is_administrable($category) && $category != 'none' && $module->is_authorized())
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

		$customize = $this->array();
		$theme     = NeoFrag()->model2('addon')->get('theme', $this->config->nf_default_theme, FALSE);

		if (@$theme->addon()->controller('admin'))
		{
			$customize	->set('title',  'Apparence')
						->set('icon',   'fas fa-paint-brush')
						->set('access', $this->user->admin)
						->set('url',   'admin/addons/customize/'.$theme->url());
		}

		$this->data->set('sidebar', [
			'panel' => FALSE,
			'links' => array_filter([
				[
					'title' => 'Tableau de bord',
					'icon'  => 'fas fa-tachometer-alt',
					'url'   => 'admin'
				],
				[
					'title'  => 'Paramètres',
					'icon'   => 'fas fa-cogs',
					'access' => $this->user->admin,
					'url'    => 'admin/settings'
				],
				$customize->__toArray(),
				[
					'title' => 'Utilisateurs',
					'icon'  => 'fas fa-users',
					'url'   => [
						['title' => 'Membres / Groupes',      'icon'  => 'fas fa-users',        'access' => $this->user->admin, 'url' => 'admin/user'],
						['title' => 'Sessions',               'icon'  => 'fas fa-globe',        'access' => $this->user->admin, 'url' => 'admin/user/sessions'],
						['title' => 'Permissions',            'icon'  => 'fas fa-unlock-alt',   'access' => $this->user->admin, 'url' => 'admin/access'],
						//['title' => 'Bannissement',           'icon'  => 'fas fa-bomb',         'access' => $this->user->admin, 'url' => 'admin/user/ban']
					]
				],
				[
					'title' => 'Contenu',
					'icon'  => 'fas fa-edit',
					'url'   => $content_submenu['default']
				],
				[
					'title' => 'Gaming',
					'icon'  => 'fas fa-gamepad',
					'url'   => $content_submenu['gaming']
				],
				[
					'title' => 'Live Editor',
					'icon'  => 'fas fa-desktop',
					'access' => $this->user->admin,
					'url'   => 'admin/live-editor'
				],
				[
					'title' => 'Statistiques',
					'icon'  => 'far fa-chart-bar',
					'access' => $this->user->admin,
					'url'    => 'admin/statistics'
				]
			])
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
