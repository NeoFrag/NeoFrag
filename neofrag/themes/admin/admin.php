<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

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
		$content_submenu = array();
		
		foreach ($this->addons->get_modules() as $module)
		{
			if ($module->is_administrable())
			{
				$content_submenu[] = array(
					'title' => $module->get_title(),
					'icon'  => $this->template->parse($module->icon),
					'url'   => 'admin/'.$module->name.'.html'
				);
			}
		}

		array_natsort($content_submenu, function($a){
			return $a['title'];
		});

		$this	->css('font.open-sans.300.400.600.700.800')
				->css('font.roboto.100.300.400.500.700.900')
				->css('font.signika-negative.400.600')
				->css('sb-admin-2')
				->css('font-awesome.min')
				->css('style')
				->css('neofrag.user')
				->js('metisMenu.min')
				->js('neofrag.navigation')
				->js('slideout.min')
				->add_data('menu', array(
					array(
						'title' => $this('dashboard'),
						'icon'  => 'fa-dashboard',
						'url'   => 'admin.html'
					),
					array(
						'title' => $this('settings'),
						'icon'  => 'fa-cogs',
						'url'   => array(
							array(
								'title' => $this('configuration'),
								'icon'  => 'fa-wrench',
								'url'   => 'admin/settings.html'
							),
							array(
								'title' => $this('maintenance'),
								'icon'  => 'fa-power-off',
								'url'   => 'admin/settings/maintenance.html'
							),
							array(
								'title' => $this('addons'),
								'icon'  => 'fa-puzzle-piece',
								'url'   => 'admin/addons.html'
							)
						)
					),
					array(
						'title' => $this('users'),
						'icon'  => 'fa-users',
						'url'   => array(
							array(
								'title' => $this('members'),
								'icon'  => 'fa-users',
								'url'   => 'admin/members.html'
							),
							array(
								'title' => $this('sessions'),
								'icon'  => 'fa-globe',
								'url'   => 'admin/members/sessions.html'
							),
							/*array(
								'title' => 'Profil',
								'icon'  => 'fa-user',
								'url'   => 'admin/user.html'
							),*/
							array(
								'title' => $this('permissions'),
								'icon'  => 'fa-unlock-alt',
								'url'   => 'admin/access.html'
							),
							array(
								'title' => $this('ban'),
								'icon'  => 'fa-bomb',
								'url'   => 'admin/members/ban.html'
							)
						)
					),
					array(
						'title' => $this('content'),
						'icon'  => 'fa-edit',
						'url'   => $content_submenu
					),
					array(
						'title' => $this('design'),
						'icon'  => 'fa-paint-brush',
						'url'   => array(
							array(
								'title' => $this('themes'),
								'icon'  => 'fa-tint',
								'url'   => 'admin/addons.html#themes'
							),
							array(
								'title' => $this('liveditor'),
								'icon'  => 'fa-desktop',
								'url'   => 'live-editor.html'
							)
						)
					),
					array(
						'title' => $this('security'),
						'icon'  => 'fa-shield',
						'url'   => array(
							array(
								'title' => $this('notifications'),
								'icon'  => 'fa-flag',
								'url'   => 'admin/notifications.html'
							),
							array(
								'title' => $this('database'),
								'icon'  => 'fa-database',
								'url'   => 'admin/database.html'
							),
							/*array(
								'title' => 'Rapport d\'erreurs',
								'icon'  => 'fa-exclamation-triangle',
								'url'   => 'admin/logs.html'
							),
							array(
								'title' => 'Analyse des fichiers',
								'icon'  => 'icons/exclamation-shield.png',
								'url'   => 'admin/logs.html'
							),*/
							array(
								'title' => $this('server'),
								'icon'  => 'fa-cogs',
								'url'   => 'admin/phpinfo.html'
							)
						)
					),
					/*array(
						'title' => 'Statistiques',
						'icon'  => 'fa-signal',
						'url'   => array(
							array(
								'title' => 'Visites',
								'icon'  => 'icons/chart-pie-separate.png',
								'url'   => 'admin/statistics.html'
							),
							array(
								'title' => 'Performances',
								'icon'  => 'icons/application-monitor.png',
								'url'   => 'admin/statistics/performance.html'
							)
						)
					),*/
					array(
						'title' => $this('about'),
						'icon'  => 'fa-info',
						'url'   => 'admin/about.html'
					)
				));

		//TODO vérification de la licence pour afficher une alerte
		
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

/*
NeoFrag Alpha 0.1.4
./neofrag/themes/admin/admin.php
*/