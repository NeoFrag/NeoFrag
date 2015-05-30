$(function(){
	$('body').on('click', 'a.delete', function(){
		if (!$('.delete.alert').length){
			$.ajax({
				url: $(this).attr('href'),
				dataType: 'text',
				success: function(data){
					$('	<div class="modal fade" tabindex="-1" role="dialog">\
							<div class="modal-dialog">\
								<div class="modal-content">\
									'+data+'\
								</div>\
							</div>\
						</div>').appendTo('body').modal();
				}
			});
		}

		return false;
	});

	confirm_deletion = function(anchor){
		$.ajax({
			url: $(anchor).attr('href'),
			type: 'POST',
			data: $(anchor).attr('data-form-id')+'[]=delete',
			dataType: 'text',
			success: function(data){
				if (data == 'OK'){
					$(anchor).parents('.alert').alert('close');
					if ((table = $(anchor).parents('.alert').nextAll('.table-area')).length){
						$.ajax({
							url: window.location.pathname,
							type: 'POST',
							data: 'table_id='+$(table).attr('data-table-id'),
							dataType: 'json',
							success: function(data){
								$(table).children('.table-content').html(data.content);
							}
						});
					}
					else{
						document.location.reload();
					}
				}
				else{
					$(anchor).parents('.alert').html('<button data-dismiss="alert" class="close" type="button">×</button>'+data);
				}
			}
		});

		return false;
	};
});