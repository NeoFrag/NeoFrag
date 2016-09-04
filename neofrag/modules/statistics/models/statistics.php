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

class m_statistics_m_statistics extends Model
{
	public function get_statistics($filters = NULL)
	{
		$statistics = [];
		$colors     = ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];

		$i = 0;

		foreach ($this->addons->get_modules() as $module)
		{
			if ($controller = $module->load->controller('statistics'))
			{
				foreach ($controller->statistics() as $name => $statistic)
				{
					if ($filters === NULL || in_array($module->name.'-'.$name, $filters))
					{
						$statistics[$module->name.'-'.$name] = array_merge($statistic, [
							'title' => $module->load->lang($statistic['title'], NULL),
							'color' => $colors[$i % 10]
						]);
					}
					
					$i++;
				}
			}
		}

		return $statistics;
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/statistics/models/statistics.php
*/