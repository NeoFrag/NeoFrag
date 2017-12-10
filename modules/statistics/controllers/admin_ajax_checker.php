<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Statistics\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function index()
	{
		if ($check = post_check(['modules', 'start', 'end', 'period'], $this->form()->token('sq6fswkfb81n0lu4cb7eyb3tuixcovla')))
		{
			$this->extension('json');

			$periods = [
				'hour'  => [function($a){ return 'DATE_FORMAT('.$a.', "%Y-%m-%d %H")'; },                                     'Y-m-d H', new \DateInterval('PT1H'), function(&$a) { $a = $a->setTime(0, 0); }],
				'day'   => [function($a){ return 'DATE_FORMAT('.$a.', "%Y-%m-%d")'; },                                        'Y-m-d',   new \DateInterval('P1D')],
				'week'  => [function($a){ return 'CONCAT_WS("-", DATE_FORMAT('.$a.', "%x"), LPAD(WEEK('.$a.', 3), 2, 0))'; }, 'o-W',     new \DateInterval('P1W'),  function(&$a) { $a = $a->modify('previous week'); }],
				'month' => [function($a){ return 'DATE_FORMAT('.$a.', "%Y-%m")'; },                                           'Y-m',     new \DateInterval('P1M'),  function(&$a) { $a = $a->modify('first day of'); }],
				'year'  => [function($a){ return 'DATE_FORMAT('.$a.', "%Y")'; },                                              'Y',       new \DateInterval('P1Y'),  function(&$a) { $a = $a->modify('01 january'); }]
			];

			$start = date_create_from_format('d/m/Y', $check['start']);
			$end = date_create_from_format('d/m/Y', $check['end']);

			$this->session	->set('statistics', 'period', $check['period'])
							->set('statistics', 'start', $start->getTimestamp())
							->set('statistics', 'end', $end->getTimestamp())
							->set('statistics', 'date', time());

			return [$this->model()->get_statistics(array_filter($check['modules'])), $start, $end->setTime(23, 59, 59), $periods[$check['period']]];
		}
	}
}
