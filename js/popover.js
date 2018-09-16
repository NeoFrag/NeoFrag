var popover = function($elem){
	var url = $elem.data('popover-ajax');

	if (!$elem.data('bs.popover')){
		$.ajax({
			url: url,
			cache: false,
			success: function(data){
				$elem.popover({
					content: data,
					trigger: 'hover',
					placement: 'auto',
					container: 'body',
					html: true
				}).popover('show');

				$('body').trigger('nf.load');
			}
		});
	}
};

$(function(){
	$(document).on('mouseover', '[data-popover-ajax]', function(e){
		popover($(this));
		e.preventDefault();
	});
});
