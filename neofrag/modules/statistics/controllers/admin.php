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

class m_statistics_c_admin extends Controller_Module
{
	public $administrable = FALSE;

	public function index()
	{
		$this	->js('statistics')
				->js('highstock');

		return [
			new Row(
				new Col(
					new Panel([
						'title'   => 'Statistiques',
						'icon'    => 'fa-bar-chart',
						'content' => $this	->form
											->set_id('sq6fswkfb81n0lu4cb7eyb3tuixcovla')
											->add_rules('statistics')
											->fast_mode()
											->display()
					])
				, 'col-md-4 col-lg-3'),
				new Col(
					new Panel([
						'content' => '<div id="highcharts"></div>',
						'body'    => FALSE
					])
				, 'col-md-8 col-lg-9')
			)
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/statistics/controllers/admin.php
*/