$(function(){
	var show_move_modal = function(){
		var $modal = $('#modal-topic-move');
		
		if ($modal.length){
			$modal.modal('show');
			return true;
		}
		
		return false;
	};
	
	$('.topic-move').click(function(){
		if (!show_move_modal()) {
			$.get($(this).data('action'), function(data){
				$('body').append(data);
				show_move_modal();
			});
		}
	});
});