$.ajax({
	   
	url: '{base_url}ajax/settings/javascript.html',
	type: 'POST',
	data: 'time_zone='+new Date().getTimezoneOffset(),
	success: function(data){
		
			console.log(data);
		if (data == 'RELOAD'){
			
			//document.location.reload();
		}
	}
});