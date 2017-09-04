<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Settings\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function maintenance()
	{
		$this	->extension('json')
				->config('nf_maintenance', (bool)post('closed'), 'bool');

		return [
			'status' => $this->config->nf_maintenance
		];
	}
}
