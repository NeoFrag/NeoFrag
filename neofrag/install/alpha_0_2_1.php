<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2_1 extends Install
{
	public function up()
	{
		$this->db()->insert('nf_addon', [
			'type_id' => 1,
			'name'    => 'tools',
			'data'    => 'a:1:{s:7:"enabled";b:1;}'
		]);

		$this->db()->insert('nf_addon', [
			'type_id' => 3,
			'name'    => 'about',
			'data'    => 'a:1:{s:7:"enabled";b:1;}'
		]);

		$this->config	->unset('nf_maintenance_facebook')
						->unset('nf_maintenance_google-plus')
						->unset('nf_maintenance_steam')
						->unset('nf_maintenance_twitch')
						->unset('nf_maintenance_twitter');

		$this->config('images_per_page', 24, 'int');

		$this->db()->insert('nf_addon', [
			'type_id' => 2,
			'name'    => 'azuro',
			'data'    => ''
		]);

		$this->config('nf_update_callback', serialize(['alpha_0_2_1']), 'string');

		$zones = [4, 3, 1, 0, 2, 5];

		foreach ($this->db()->select('disposition_id', 'zone')
							->from('nf_dispositions')
							->where('theme', 'default')
							->get() as $disposition)
		{
			$this->db()	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'zone' => array_search($disposition['zone'], $zones) + 100
						]);
		}

		$this->db()	->where('theme', 'default')
					->update('nf_dispositions', 'zone = zone - 100');

		foreach ([
				'css/delete.css',
				'css/notify.css',
				'js/select.js',
				'modules/contact/lang/en.php',
				'modules/contact/lang/fr.php',
				'modules/events/js/lang-all.js',
				'modules/forum/css/move.css',
				'modules/forum/js/move.js',
				'modules/forum/lang/en.php',
				'modules/forum/lang/fr.php',
				'modules/gallery/css/gallery.css',
				'modules/gallery/js/gallery.js',
				'modules/gallery/js/modal-carousel.js',
				'modules/gallery/js/preview.js',
				'modules/gallery/lang/en.php',
				'modules/gallery/lang/fr.php',
				'modules/gallery/views/admin_gallery.tpl.php',
				'modules/gallery/views/index.tpl.php',
				'modules/gallery/views/upload.tpl.php',
				'modules/games/lang/en.php',
				'modules/games/lang/fr.php',
				'modules/members/lang/en.php',
				'modules/members/lang/fr.php',
				'modules/members/models/members.php',
				'modules/news/lang/en.php',
				'modules/news/lang/fr.php',
				'modules/pages/views/search/detail.tpl.php',
				'modules/search/css/sass/search.scss',
				'modules/search/css/search.css',
				'modules/settings/js/maintenance.js',
				'modules/talks/lang/en.php',
				'modules/talks/lang/fr.php',
				'modules/teams/lang/en.php',
				'modules/teams/lang/fr.php',
				'modules/user/views/messages/inbox.tpl.php',
				'modules/user/views/messages/menu.tpl.php',
				'modules/user/views/messages/replies.tpl.php',
				'themes/admin/css/sass/sections/_main.scss',
				'themes/admin/css/sass/sections/_navigation.scss',
				'themes/admin/css/sass/sections/_sidebar.scss',
				'themes/admin/images/bg.jpg',
				'themes/admin/langs/en.php',
				'themes/admin/views/navigation.tpl.php',
				'themes/default/controllers/admin.php',
				'themes/default/css/sass/_base.scss',
				'themes/default/css/sass/_general.scss',
				'themes/default/css/sass/_reset.scss',
				'themes/default/css/sass/bootstrap/_buttons.scss',
				'themes/default/css/sass/bootstrap/_form.scss',
				'themes/default/css/sass/bootstrap/_list_group.scss',
				'themes/default/css/sass/bootstrap/_modal.scss',
				'themes/default/css/sass/bootstrap/_utilities.scss',
				'themes/default/css/sass/neofrag/_list_right.scss',
				'themes/default/css/sass/neofrag/_pagination.scss',
				'themes/default/css/sass/neofrag/_stars.scss',
				'themes/default/css/sass/neofrag/_user_badge.scss',
				'themes/default/css/sass/neofrag/modules/_forum.scss',
				'themes/default/css/sass/neofrag/modules/_user.scss',
				'themes/default/css/sass/neofrag/widgets/_breadcrumb.scss',
				'themes/default/css/sass/neofrag/widgets/_header.scss',
				'themes/default/css/sass/neofrag/widgets/_navigation.scss',
				'themes/default/css/sass/sections/_content.scss',
				'themes/default/css/sass/sections/_header.scss',
				'themes/default/css/sass/styles/_card.scss',
				'themes/default/css/sass/styles/_row.scss',
				'themes/default/css/style.maintenance.css',
				'themes/default/images/backgrounds/default.jpg',
				'themes/default/images/backgrounds/maintenance.jpg',
				'themes/default/images/gray_dark.png',
				'themes/default/images/gray_light.png',
				'themes/default/images/live_editor/black.png',
				'themes/default/images/live_editor/dark.png',
				'themes/default/images/live_editor/default.png',
				'themes/default/images/live_editor/light.png',
				'themes/default/images/live_editor/orange.png',
				'themes/default/images/live_editor/panel_dark.png',
				'themes/default/images/live_editor/panel_default.png',
				'themes/default/images/live_editor/panel_red.png',
				'themes/default/images/live_editor/white.png',
				'themes/default/images/logo_effect.png',
				'themes/default/images/logo_shadow1.png',
				'themes/default/images/logo_shadow2.png',
				'themes/default/images/logo_shadow3.png',
				'themes/default/images/logo_shadow4.png',
				'themes/default/images/navbar.png',
				'themes/default/langs/en.php',
				'themes/default/views/actions.tpl.php',
				'themes/default/views/admin/index.tpl.php',
				'themes/default/views/admin/menu.tpl.php',
				'themes/default/views/live_editor/row.tpl.php',
				'themes/default/views/live_editor/widget.tpl.php',
				'themes/default/views/logo.tpl.php',
				'themes/default/views/maintenance.tpl.php',
				'widgets/forum/lang/en.php',
				'widgets/forum/lang/fr.php',
				'widgets/gallery/lang/en.php',
				'widgets/gallery/lang/fr.php',
				'widgets/header/lang/en.php',
				'widgets/header/lang/fr.php',
				'widgets/members/lang/en.php',
				'widgets/members/lang/fr.php',
				'widgets/navigation/views/horizontal.tpl.php',
				'widgets/navigation/views/vertical.tpl.php',
				'widgets/news/lang/en.php',
				'widgets/news/lang/fr.php',
				'widgets/search/css/search.css',
				'widgets/slider/lang/en.php',
				'widgets/slider/lang/fr.php',
				'widgets/talks/lang/en.php',
				'widgets/talks/lang/fr.php',
				'widgets/teams/lang/en.php',
				'widgets/teams/lang/fr.php'
			] as $file)
		{
			unlink($file);
		}
	}

	public function post()
	{
		$this->theme('azuro')->install();
		$this->config('nf_default_theme', 'azuro');
	}
}
