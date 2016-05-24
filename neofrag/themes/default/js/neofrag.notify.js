function notify(message, type) {
	if (typeof type == 'undefined') {
		type = 'success';
	}
	
	$(function(){
		$.notify({
			message: message
		},{
			mouse_over: 'pause',
			newest_on_top: true,
			type: type
		});
	});
}