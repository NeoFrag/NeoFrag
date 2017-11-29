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
				->js('monitoring')
				->js('modal')
				->js('jquery.knob')
				->js_load('$(\'.knob\').knob();')
				->js('jquery.mCustomScrollbar.min')
				->css('jquery.mCustomScrollbar.min')
				->js('bootstrap-treeview.min')
				->css('bootstrap-treeview.min');

		return $this->row(
			$this	->col(
						$this->panel()->body($this->view('monitoring'), FALSE),
						$this	->panel()
								->heading('<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-modal-ajax="'.url('admin/ajax/monitoring/phpinfo').'">'.icon('fa-info').'</a></div>Informations serveur', 'fa-info-circle')
								->body($this->view('infos', [
									'check'   => $this->model()->check_server()
								]))
								->color('default panel-infos')
					)
					->size('col-4 col-lg-3'),
			$this	->col(
						$this->row(
							$this	->col(
										$this	->panel()
												->heading('<div class="pull-right"><a class="btn btn-xs btn-default refresh">'.icon('fa-refresh').'</a></div>Notifications', 'fa-bell-o')
												->body('<table class="table table-notifications m-0"></table>')
												->color('default panel-notifications')
									)
									->size('col-6 col-lg-8'),
							$this	->col(
										$this	->panel()
												->heading('<div class="pull-right"><a class="btn btn-xs btn-default" href="#" data-toggle="modal" data-target="#modal-backup">'.icon('fa-floppy-o').'</a></div>Stockage', 'fa-files-o')
												->body($this->view('storage'))
												->footer($this->view('storage-footer'))
												->color('default panel-storage')
									)
									->size('col-6 col-lg-4')
						),
						$this->row(
							$this->col(
								$this	->panel()
										->heading('Votre installation NeoFrag', 'fa-heartbeat')
										->body('<div id="tree"></div>', FALSE)
							)
						)
					)
					->size('col-8 col-lg-9')
		);
	}
}
