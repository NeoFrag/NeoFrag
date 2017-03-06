$(function(){
	var paddingBody = function(){
		$('body').css('padding-bottom', $('.debugbar').outerHeight());
	};

	paddingBody();
	
	$('.debugbar-tab').click(function(){
		if (!$(this).hasClass('active')){
			var tab = $(this).data('debugbar');
			$('.debugbar').addClass('active');
			$('.debugbar-tab.active, .debugbar-pane.active').removeClass('active');
			$(this).addClass('active');
			$('.debugbar-pane[data-tab="'+tab+'"]').addClass('active');
			$.post('<?php echo url('ajax/settings/debugbar'); ?>', {tab: tab});
			paddingBody();
		}
	});

	var resizing = false;
	
	$('.debugbar-resize').mousedown(function(e){
		resizing = true;
		
		var offset = $(window).height() - $('.debugbar > nav').outerHeight() + e.pageY - $(this).offset().top;

		$(document).mousemove(function(e){
			if (resizing) {
				var height = offset - e.clientY;
				
				if (height > 200){
					$('.debugbar-content').height(height);
					paddingBody();
				}
			}
		});
	});
	
	$(document).mouseup(function(){
		if (resizing) {
			$.post('<?php echo url('ajax/settings/debugbar'); ?>', {height: $('.debugbar-content').innerHeight()});
			resizing = false;
		}
	});

	$('.debugbar-close').click(function(){
		$('.debugbar.active, .debugbar .active').removeClass('active');
		$.post('<?php echo url('ajax/settings/debugbar'); ?>', {tab: ''});
		paddingBody();
	});

	$('.debugbar-content').mCustomScrollbar({
		theme: 'dark'
	});
});