<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring;

use NF\NeoFrag\Addons\Module;

class Monitoring extends Module
{
	public $title         = 'Monitoring';
	public $description   = '';
	public $icon          = 'fa-heartbeat';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $routes        = [];
	public $path          = __FILE__;
	public $admin         = FALSE;

	public function need_checking()
	{
		return ($this->config->nf_monitoring_last_check < ($time = strtotime('01:00')) && time() > $time) || !file_exists('cache/monitoring/monitoring.json');
	}

	public function display()
	{
		if (file_exists('cache/monitoring/monitoring.json'))
		{
			foreach (array_merge(array_fill_keys(['danger', 'warning', 'info'], 0), array_count_values(array_map(function($a){
				return $a[1];
			}, json_decode(file_get_contents('cache/monitoring/monitoring.json'))->notifications))) as $class => $count)
			{
				if ($count)
				{
					return '<span class="pull-right label label-'.$class.'">'.$count.'</span>';
				}
			}
		}

		return '';
	}
}
