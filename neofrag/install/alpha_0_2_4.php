<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2_4 extends Install
{
	public function up()
	{
		foreach (['background_position', 'background_color', 'text_color'] as $var)
		{
			$this->config('nf_maintenance_'.$var, trim($this->config->{'nf_maintenance_'.$var}));
		}
	}
}
