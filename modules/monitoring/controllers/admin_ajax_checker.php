<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_monitoring_c_admin_ajax_checker extends Controller_Module
{
	public function index()
	{
		if ($check = post_check('refresh'))
		{
			$this->extension('json');
			return [$check['refresh']];
		}
	}

	public function backup()
	{
		$this->extension('json');
		return [];
	}

	public function update()
	{
		$this->extension('json');
		return [];
	}
}
