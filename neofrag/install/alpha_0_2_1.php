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
	}

	public function post()
	{
		$this->theme('azuro')->install();
		$this->config('nf_default_theme', 'azuro');
	}
}
