$(function(){
	$('[data-profiler]').click(function(){
										
		if($(this).find('.icon-chevron-up').length == 1){
			
			$(this).find('.icon-chevron-up').removeClass('icon-chevron-up').addClass('icon-chevron-down');
			$(this).nextAll('.profiler-block:first').css('display', 'none');
			hide = 1;
		}
		else if($(this).find('.icon-chevron-down').length == 1){
			
			$(this).find('.icon-chevron-down').removeClass('icon-chevron-down').addClass('icon-chevron-up');
			$(this).nextAll('.profiler-block:first').css('display', 'block');
			hide = 0;
		}
		else if($(this).find('.icon-remove').length == 1){
			
			$(this).find('.icon-remove').removeClass('icon-remove').addClass('icon-plus');
			$(this).nextAll('.profiler-block:first').css('display', 'none');
			hide = 1;
		}
		else if($(this).find('.icon-plus').length == 1){
			
			$(this).find('.icon-plus').removeClass('icon-plus').addClass('icon-remove');
			$(this).nextAll('.profiler-block:first').css('display', 'block');
			hide = 0;
		}
		
		$.ajax({
			   
			url: '<?php echo url('ajax/settings/profiler.html'); ?>',
			type: 'POST',
			data: 'part='+$(this).data('profiler')+'&hide='+hide
		});
		
		return false;
	});
	
	$('[data-profiler]').each(function(){
									   
		if($(this).find('.icon-plus').length == 1 || $(this).find('.icon-chevron-down').length == 1){
			
			$(this).nextAll('.profiler-block:first').css('display', 'none');
		}
	});
});