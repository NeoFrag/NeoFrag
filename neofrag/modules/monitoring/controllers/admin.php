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

class m_monitoring_c_admin extends Controller_Module
{
	public $administrable = FALSE;

	public function index()
	{
		$this	->css('monitoring')
				->css('phpinfo')
				->js('monitoring')
				->js('jquery.knob')
				->js_load('$(\'.knob\').knob();')
				->js('jquery.mCustomScrollbar.min')
				->css('jquery.mCustomScrollbar.min')
				->js('bootstrap-treeview.min')
				->css('bootstrap-treeview.min');

		$extensions = get_loaded_extensions();
		natcasesort($extensions);

		$phpinfo = [new Panel([
			'content' => $this->load->view('phpinfo', array_merge($this->model()->get_info(), [
				'extensions' => $extensions
			]))
		])];

		ob_start();
		phpinfo();

		if (preg_match_all('#(?:<h1>(.*?)</h1>.*?)?(?:<h2>(.*?)</h2>.*?)?<table.*?>(.*?)</table>#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		{
			foreach (array_offset_left($matches) as $match)
			{
				if ($match[1])
				{
					$phpinfo[] = new Panel([
						'title' => $match[1] ? '<h1 class="text-center no-margin">'.$match[1].'</h1>' : ''
					]);
				}
				
				$phpinfo[] = new Panel([
					'title'   => $match[2] ? '<h2 class="text-center no-margin">'.$match[2].'</h2>' : '',
					'content' => '<table class="table table-hover table-striped">'.$match[3].'</table>',
					'body'    => FALSE
				]);
			}
		}

		return [
			new Row(
				new Col(
					new Panel([
						'content' => $this->load->view('monitoring'),
						'body'    => FALSE
					]),
					new Panel([
						'title'   => '<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#modal-phpinfo">'.icon('fa-info').'</a></div>Informations serveur',
						'style'   => 'panel-default panel-infos',
						'icon'    => 'fa-info-circle',
						'content' => $this->load->view('infos', [
							'check'   => $this->model()->check_server(),
							'phpinfo' => $phpinfo
						])
					])
				, 'col-md-4 col-lg-3'),
				new Col(
					new Row(
						new Col(
							new Panel([
								'title'   => '<div class="pull-right"><a class="btn btn-xs btn-default refresh">'.icon('fa-refresh').'</a></div>Notifications',
								'icon'    => 'fa-bell-o',
								'content' => '<table class="table table-notifications no-margin"></table>',
								'style'   => 'panel-default panel-notifications'
							])
						, 'col-md-6 col-lg-8'),
						new Col(
							new Panel([
								'title'   => '<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#modal-backup">'.icon('fa-floppy-o').'</a></div>Stockage',
								'icon'    => 'fa-files-o',
								'content' => $this->load->view('storage'),
								'style'   => 'panel-default panel-storage',
								'footer'  => $this->load->view('storage-footer'),
							])
						, 'col-md-6 col-lg-4')
					),
					new Row(
						new Col(
							new Panel([
								'title'   => 'Votre installation NeoFrag',
								'icon'    => 'fa-heartbeat',
								'content' => '<div id="tree"></div>',
								'body'    => FALSE
							])
						, 'col-md-12')
					)
				, 'col-md-8 col-lg-9')
			)
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/monitoring/controllers/admin.php
*/