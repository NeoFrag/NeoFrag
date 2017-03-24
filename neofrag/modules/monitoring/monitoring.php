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

class m_monitoring extends Module
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

/*
NeoFrag Alpha 0.1.6
./neofrag/modules/monitoring/monitoring.php
*/