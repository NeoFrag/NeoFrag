$('body').on('nf.load', function(){
	var data = $.makeArray($.unique($('[data-geolocalisation]').map(function(){
		return $(this).attr('data-geolocalisation');
	}))).filter(function(a){
		return a;
	});

	if (data.length){
		$.ajax({
			url: 'https://neofr.ag/geolocalisation.json',
			type: 'POST',
			data: {
				ip_address: data
			},
			crossDomain: false,
			success: function(data){
				$.each(data, function(ip, data){
					$('[data-geolocalisation="'+ip+'"]').replaceWith('<img src="'+(data['flag'] ? '<?php echo url('images/flags') ?>/'+data['flag'] : '<?php echo image('icons/user-silhouette-question.png') ?>')+'" data-toggle="tooltip" title="'+data['location']+'" style="margin-right: 10px;" alt="" />');
				});
			}
		})
	}
});
