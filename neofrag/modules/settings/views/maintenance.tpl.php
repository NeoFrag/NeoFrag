<div class="row">
	<div class="col-md-12 text-center">
		<div class="btn-group switch">
			<a href="#" class="btn <?php echo ($opened = !$NeoFrag->config->nf_maintenance) ? 'btn-success' : 'btn-default'; ?>"><?php echo icon($opened ? 'fa-toggle-on' : 'fa-toggle-off').' '.i18n('opened'); ?></a>
			<a href="#" class="btn <?php echo !$opened ? 'btn-danger' : 'btn-default'; ?>"><?php echo icon(!$opened ? 'fa-toggle-on' : 'fa-toggle-off').' '.i18n('closed'); ?></a>
		</div>
	</div>
</div>