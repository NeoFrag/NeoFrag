<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Statistics\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->js('statistics')
				->js('highstock');

		return [
			$this->row(
				$this	->col(
							$this	->panel()
									->heading('Statistiques', 'fa-bar-chart')
									->body($this	->form
													->set_id('sq6fswkfb81n0lu4cb7eyb3tuixcovla')
													->add_rules('statistics')
													->fast_mode()
													->display())
						)
						->size('col-md-4 col-lg-3'),
				$this	->col($this->panel()->body('<div id="highcharts"></div>', FALSE))
						->size('col-md-8 col-lg-9')
			)
		];
	}
}
