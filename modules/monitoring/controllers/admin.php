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
								->heading('<div class="float-right" data-toggle="tooltip" title="'.$this->lang('Détails').'"><a class="btn btn-outline-info btn-sm" href="#" data-modal-ajax="'.url('admin/ajax/monitoring/phpinfo').'">'.icon('fas fa-info').'</a></div>Informations serveur', 'fas fa-info-circle')
								->body($this->view('infos', [
									'check'   => $this->model()->check_server()
								]))
								->color('default panel-infos')
					)
					->size('col-12 col-lg-3'),
			$this	->col(
						$this->row(
							$this	->col(
										$this	->panel()
												->heading('<div class="float-right" data-toggle="tooltip" title="'.$this->lang('Actualiser').'"><a class="btn btn-outline-info btn-sm refresh" href="#">'.icon('fas fa-sync').'</a></div>Notifications', 'far fa-bell')
												->body('<table class="table table-notifications m-0"></table>')
												->color('default panel-notifications')
									)
									->size('col-12 col-lg-8'),
							$this	->col(
										$this	->panel()
												->heading('<div class="float-right" data-toggle="tooltip" title="'.$this->lang('Sauvegarder').'"><a class="btn btn-outline-info btn-sm" href="#" data-toggle="modal" data-target="#modal-backup">'.icon('far fa-save').'</a></div>Stockage', 'far fa-copy')
												->body($this->view('storage'))
												->footer($this->view('storage-footer'))
												->color('default panel-storage')
									)
									->size('col-12 col-lg-4')
						),
						$this->row(
							$this->col(
								$this	->panel()
										->heading('Votre installation NeoFrag', 'fas fa-heartbeat')
										->body('<div id="tree"></div>', FALSE)
							)
						)
					)
					->size('col-12 col-lg-9')
		);
	}

	public function update($version)
	{
		$this->theme('admin')->js('update');

		return $this->modal('Mise à jour de NeoFrag', 'fas fa-rocket')
					->large()
					->set_id('modal-update')
					->body('<div class="update-features">
								'.$version->features.'
							</div>
							<hr />
							<div class="steps-body text-center">
								<div class="row" style="padding: 0 110px;">
									<div class="col">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="50,50"></div>
										</div>
									</div>
									<div class="col">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="100"></div>
										</div>
									</div>
									<div class="col">
										<div class="progress">
											<div class="progress-bar" role="progressbar" data-step="95,5"></div>
										</div>
									</div>
								</div>
								<div class="row steps-legends">
									<div class="col">
										<div class="step">
											'.icon('fas fa-sync').'
										</div>
										Lancement
									</div>
									<div class="col">
										<div class="step">
											'.icon('far fa-save').'
										</div>
										Sauvegarde
									</div>
									<div class="col">
										<div class="step">
											'.icon('far fa-arrow-alt-circle-down').'
										</div>
										Téléchargement
									</div>
									<div class="col">
										<div class="step">
											'.icon('fas fa-cog').'
										</div>
										Installation
									</div>
								</div>
							</div>')
					->submit('Lancer la mise à jour')
					->cancel();
	}
}
