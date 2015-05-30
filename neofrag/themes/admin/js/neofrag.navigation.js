$(function(){
	$('.nav-sidebar > li').click(function(){
		if ($(this).children('ul').length){
			li = this;
			
			$('.nav-sidebar > li.active').each(function(){
				if (this != li){
					$(this).children('ul').hide('fast', function(){
						$(this).parent().removeClass('active');
					});
				}
			});
			
			$(this).children('ul').show('fast', function(){
				$('.nav-sidebar > li.active').removeClass('active');
				$(this).parent().addClass('active');
			});
		}

		return true;
	});
});