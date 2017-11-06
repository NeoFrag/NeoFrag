<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Monitoring\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
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

		$phpinfo = [$this	->panel()
							->body($this->view('phpinfo', array_merge($this->model()->get_info(), [
								'extensions' => $extensions
							])))];

		ob_start();
		phpinfo();

		if (preg_match_all('#(?:<h1>(.*?)</h1>.*?)?(?:<h2>(.*?)</h2>.*?)?<table.*?>(.*?)</table>#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		{
			foreach (array_offset_left($matches) as $match)
			{
				if ($match[1])
				{
					$phpinfo[] = $this->panel()->heading($match[1] ? '<h1 class="text-center no-margin">'.$match[1].'</h1>' : '');
				}

				$phpinfo[] = $this		->panel()
										->heading($match[2] ? '<h2 class="text-center no-margin">'.$match[2].'</h2>' : '')
										->body('<table class="table table-hover table-striped">'.$match[3].'</table>', FALSE);
			}
		}

		return $this->row(
			$this	->col(
						$this->panel()->body($this->view('monitoring'), FALSE),
						$this	->panel()
								->heading('<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#modal-phpinfo">'.icon('fa-info').'</a></div>Informations serveur', 'fa-info-circle')
								->body($this->view('infos', [
									'check'   => $this->model()->check_server(),
									'phpinfo' => $phpinfo
								]))
								->color('default panel-infos')
					)
					->size('col-md-4 col-lg-3'),
			$this	->col(
						$this->row(
							$this	->col(
										$this	->panel()
												->heading('<div class="pull-right"><a class="btn btn-xs btn-default refresh">'.icon('fa-refresh').'</a></div>Notifications', 'fa-bell-o')
												->body('<table class="table table-notifications no-margin"></table>')
												->color('default panel-notifications')
									)
									->size('col-md-6 col-lg-8'),
							$this	->col(
										$this	->panel()
												->heading('<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#modal-backup">'.icon('fa-floppy-o').'</a></div>Stockage', 'fa-files-o')
												->body($this->view('storage'))
												->footer($this->view('storage-footer'))
												->color('default panel-storage')
									)
									->size('col-md-6 col-lg-4')
						),
						$this->row(
							$this->col(
								$this	->panel()
										->heading('Votre installation NeoFrag', 'fa-heartbeat')
										->body('<div id="tree"></div>', FALSE)
							)
						)
					)
					->size('col-md-8 col-lg-9')
		);
	}
}
