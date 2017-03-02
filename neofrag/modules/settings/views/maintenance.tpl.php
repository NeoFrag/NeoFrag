<div class="row">
	<div class="col-md-12 text-center">
		<div class="btn-group switch">
			<a href="#" class="btn <?php echo ($opened = !$this->config->nf_maintenance) ? 'btn-success' : 'btn-default'; ?>"><?php echo icon($opened ? 'fa-toggle-on' : 'fa-toggle-off').' '.$this->lang('opened'); ?></a>
			<a href="#" class="btn <?php echo !$opened ? 'btn-danger' : 'btn-default'; ?>"><?php echo icon(!$opened ? 'fa-toggle-on' : 'fa-toggle-off').' '.$this->lang('closed'); ?></a>
		</div>
	</div>
</div>