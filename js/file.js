$(function(){
	$('.form-file-delete').click(function(){
		var $delete = $(this);
		var $modal = $('\
			<div class="modal fade" role="dialog">\
				<div class="modal-dialog modal-sm">\
					<div class="modal-content">\
						<div class="modal-body">\
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
							<h4 class="modal-title"><?php echo $this->lang('Supprimer le fichier ?') ?></h4>\
							<div class="text-right" style="margin-top: 15px;">\
								<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
								<button type="button" class="btn btn-danger"><?php echo $this->lang('Supprimer') ?></button>\
							</div>\
						</div>\
					</div>\
				</div>\
			</div>').appendTo('body').modal();

		$modal.on('hidden.bs.modal', function(){
			$(this).remove();
		});

		$modal.find('.btn-danger:first').on('click', function(){
			$('[name="'+$delete.data('input')+'"]').before('<input type="hidden" name="'+$delete.data('input')+'" value="delete" />');
			$delete.parents('.thumbnail:first').parent().remove();
			$modal.modal('hide');
		});

		return false;
	});
});
