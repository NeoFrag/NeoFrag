<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Settings\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Ajax_Checker extends Module_Checker
{
	public function humans()
	{
		if ($this->url->request == 'humans.txt' && $this->config->nf_humans_txt)
		{
			$this->extension('txt');
			return [];
		}
	}

	public function robots()
	{
		if ($this->url->request == 'robots.txt' && $this->config->nf_robots_txt)
		{
			$this->extension('txt');
			return [];
		}
	}
}
