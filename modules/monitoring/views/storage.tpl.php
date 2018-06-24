<div id="modal-backup" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->lang('Fermer') ?>"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Sauvegarde</h4>
			</div>
			<div class="modal-body">
				<div class="steps-body text-center">
					<div class="row">
						<div class="col-5 col-md-offset-1">
							<div class="progress">
								<div class="progress-bar" role="progressbar"></div>
							</div>
						</div>
						<div class="col-5">
							<div class="progress">
								<div class="progress-bar" role="progressbar"></div>
							</div>
						</div>
					</div>
					<div class="row steps-legends">
						<div class="col-2">
							<div class="step">
								<?php echo icon('fa-floppy-o') ?>
							</div>
							<span class="span-legend">Lancement</span>
						</div>
						<div class="col-4 col-offset-2">
							<div class="step">
								<?php echo icon('fa-database') ?>
							</div>
							<span class="span-legend">Données</span>
						</div>
						<div class="col-2 col-offset-2">
							<div class="step">
								<?php echo icon('fa-files-o') ?>
							</div>
							<span class="span-legend">Fichiers</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>
				<a class="btn btn-primary" data-loading-text="Sauvegarde en cours...">Lancer la sauvegarde</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 text-center">
		<div class="knob-storage">
			<input class="knob" type="text" value="0" data-thickness="0.2" data-angleArc="180" data-angleOffset="-90" data-min="0" data-max="100" data-width="190" data-height="180" data-displayInput="false" data-readonly="true" autocomplete="off" />
		</div>
		<h2 id="storage-used" class="monitoring-storage-title"><?php echo icon('fa-spinner fa-spin') ?></h2>
		<span id="storage-pourcent" class="span-legend">&nbsp;</span>
	</div>
</div>
<div class="row">
	<div class="col-6 text-center">
		<h4 id="storage-free" class="monitoring-storage-title"><?php echo icon('fa-spinner fa-spin') ?></h4>
		<span class="span-legend">Libre</span>
	</div>
	<div class="col-6 text-center">
		<h4 id="storage-total" class="monitoring-storage-title"><?php echo icon('fa-spinner fa-spin') ?></h4>
		<span class="span-legend">Total</span>
	</div>
</div>
