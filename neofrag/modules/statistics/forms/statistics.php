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

if (($date = $this->session('statistics', 'date')) && time() - $date > strtoseconds('1 day'))
{
	$this->session	->destroy('statistics', 'start')
					->destroy('statistics', 'end');
}

$rules = [
	[
		'type'  => 'legend',
		'label' => 'Période'
	],
	'start' => [
		'type'  => 'date',
		'value' => $this->session('statistics', 'start') ?: strtotime('-1 year')
	],
	'end' => [
		'type'  => 'date',
		'value' => $this->session('statistics', 'end') ?: time()
	],
	'period' => [
		'type'   => 'select',
		'value'  => $this->session('statistics', 'period') ?: 'month',
		'values' => [
			'hour'  => 'Heure',
			'day'   => 'Jour',
			'week'  => 'Semaine',
			'month' => 'Mois',
			'year'  => 'Année'
		]
	],
	[
		'type'  => 'legend',
		'label' => 'Statistiques'
	],
	'modules' => [
		'type'   => 'checkbox',
		'values' => []
	]
];

if ($modules = $this->session('statistics', 'modules'))
{
	$rules['modules']['checked'] = array_fill_keys($modules, TRUE);
}

foreach ($this->model()->get_statistics() as $name => $statistic)
{
	if ($modules === NULL)
	{
		$rules['modules']['checked'][$name] = TRUE;
	}

	$rules['modules']['values'][$name] = '<b style="color: '.$statistic['color'].'">'.$statistic['title'].'</b>';
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/statistics/forms/statistics.php
*/