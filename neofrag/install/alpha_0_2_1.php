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
	}

	public function post()
	{
		$this->theme('azuro')->install();
		$this->config('nf_default_theme', 'azuro');
	}
}
