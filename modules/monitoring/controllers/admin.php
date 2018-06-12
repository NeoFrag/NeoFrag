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

	public function update()
	{
		$this->theme('admin')->js('update');

		return $this->modal('Mise à jour de NeoFrag', 'fa-rocket')
					->set_id('modal-update')
					->body('<div class="update-features">
								'.$version->features.'
							</div>
							<hr />
							<div class="steps-body text-center">
								<div class="row" style="padding: 0 110px;">
									<div class="col-md-4">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="50,50"></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="100"></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="95,5"></div>
										</div>
									</div>
								</div>
								<div class="row steps-legends">
									<div class="col-md-3">
										<div class="step">
											'.icon('fa-refresh').'
										</div>
										<span class="span-legend">Lancement</span>
									</div>
									<div class="col-md-3">
										<div class="step">
											'.icon('fa-floppy-o').'
										</div>
										<span class="span-legend">Sauvegarde</span>
									</div>
									<div class="col-md-3">
										<div class="step">
											'.icon('fa-arrow-circle-o-down').'
										</div>
										<span class="span-legend">Téléchargement</span>
									</div>
									<div class="col-md-3">
										<div class="step">
											'.icon('fa-cog').'
										</div>
										<span class="span-legend">Installation</span>
									</div>
								</div>
							</div>')
					->submit('Lancer la mise à jour')
					->cancel();
	}
}
