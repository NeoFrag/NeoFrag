<div class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php echo i18n('close'); ?>"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo icon($data['icon']).' '.$data['title']; ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $data['users']; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo i18n('close'); ?></button>
			</div>
		</div>
	</div>
</div>