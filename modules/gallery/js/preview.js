$('.thumbnail-link').on('click', function() {
	var $modal = $('\
		<div id="modal-preview" class="modal fade" role="dialog">\
			<div class="modal-dialog modal-lg">\
				<div class="modal-content">\
					<div class="modal-header">\
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Fermer</span></button>\
						<h4 class="modal-title"><i class="fa fa-photo fa-fw"></i> '+$(this).data('title')+'</h4>\
					</div>\
					<div class="modal-body text-center no-padding">\
						<img class="img-responsive" src="'+$(this).data('image')+'" alt=""/>\
					</div>\
					<div class="modal-footer">\
						<div class="pull-left text-left">\
							'+$(this).data('description')+'\
						</div>\
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>\
					</div>\
				</div>\
			</div>\
		</div>').appendTo('body').modal();

	$modal.on('shown.bs.modal', function(){
		if ($('.image-preview').width() < $('#modal-preview > .modal-dialog > .modal-content').width()){
			$('.image-preview').removeClass('img-responsive');
		}
	});
	
	$modal.on('hidden.bs.modal', function(){
		$(this).remove();
	});
});