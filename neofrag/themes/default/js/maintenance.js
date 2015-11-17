$(function(){
	$('#countdown').countdown({
		timestamp: $('#countdown').data('timestamp') * 1000,
		callback:  function(days, hours, minutes, seconds){
			if (days == 0 && hours == 0 && minutes == 0 && seconds == 0){
				location.reload();
			}
		}
	});
	
	$('.btn-login').on('click', function(){
		$('#login').animate({right: 0}, 'fast');
	});
	
	$('.btn-close').on('click', function(){
		$('#login').animate({right: -340}, 'fast');
	});
});