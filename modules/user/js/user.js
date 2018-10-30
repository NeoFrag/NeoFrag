$(function(){
	if($('.module-user .activity-message .activity-message-item').length){
		$('.module-user .activity-message').mCustomScrollbar({
			theme: 'dark',
			setHeight: 280
		});
	}

	if($('.module-user .message-list .message-item').length){
		var content_list = $('.message-list').height();
		var content_open = $('.message-open').height();

		if((content_open > 405 && content_list < 405) || (!$.trim($('.message-open').html()))){
			var height = 470;
		} else {
			var height = content_open;
		}

		$('.module-user .message-list').mCustomScrollbar({
			theme: 'dark',
			setHeight: height
		});
	}
});
