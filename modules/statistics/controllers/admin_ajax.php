<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Statistics\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function index($statistics, $start, $end, $period)
	{
		$date = clone $start;

		if (isset($period[3]))
		{
			$period[3]($date);
		}

		$dates = [];
		while ($date <= $end)
		{
			$dates[$date->format($period[1])] = 0;
			$date->add($period[2]);
		}

		$series = [];

		$this->session->set('statistics', 'modules', []);

		foreach ($statistics as $name => $statistic)
		{
			$this->session->append('statistics', 'modules', $name);

			$var = $statistic['data']();
			$data = $dates;

			foreach ($this->db	->select($period[0]($var).' as x', (!empty($statistic['group_by']) ? $statistic['group_by'] : 'COUNT(*)').' as y')
								->where($var.' >=', $start->format('Y-m-d H:i:s'))
								->where($var.' <=', $end->format('Y-m-d H:i:s'))
								->group_by('x')
								->get() as $value)
			{
				$data[$value['x']] = $value['y'];
			}

			array_walk($data, function(&$a, $b) use ($period){
				$date = $period[1] == 'o-W' ? date_create()->setISODate(substr($b, 0, 4), substr($b, 5)) : date_create_from_format($period[1], $b);
				$a = [$date->getTimestamp() * 1000, $a];
			});

			$series[] = [
				'type'  => 'line',
				'name'  => $statistic['title'],
				'data'  => array_values($data),
				'color' => $statistic['color']
			];
		}

		return $this->json($series);
	}
}
