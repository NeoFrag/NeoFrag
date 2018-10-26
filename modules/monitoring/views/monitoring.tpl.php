<div class="panel-monitoring bg-gray">
	<div class="status-legend">
		<h3>Santé du site</h3>
		<span id="monitoring-text">&nbsp;</span>
	</div>
	<div class="text-center">
		<span class="monitoring-icon-status"><?php echo icon('fa-heartbeat') ?></span>
	</div>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col monitoring-legend pt-2 pb-3">
			<h3 id="monitoring-info"><?php echo icon('fa-spinner fa-spin') ?></h3>
			<?php echo icon('fa-exclamation-circle text-info') ?> Conseils
		</div>
		<div class="col monitoring-legend pt-2 pb-3">
			<h3 id="monitoring-warning"><?php echo icon('fa-spinner fa-spin') ?></h3>
			<?php echo icon('fa-bolt text-warning') ?> Anomalies
		</div>
		<div class="col monitoring-legend pt-2 pb-3">
			<h3 id="monitoring-danger"><?php echo icon('fa-spinner fa-spin') ?></h3>
			<?php echo icon('fa-bug text-danger') ?> Erreurs
		</div>
	</div>
</div>
