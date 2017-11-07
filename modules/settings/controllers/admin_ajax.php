<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_settings_c_admin_ajax extends Controller_Module
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
