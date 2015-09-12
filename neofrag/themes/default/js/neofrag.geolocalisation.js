$(function(){
	var geolocalisation = function(){
		$('[data-geolocalisation]').each(function(){
			var $icon = $(this);
					
			$.ajax({
				url: '//www.neofrag.com/geolocalisation.json',
				type: 'POST',
				data: 'ip_address='+$icon.attr('data-geolocalisation'),
				dataType: 'json',
				crossDomain: false,
				success: function(data){
					if (data != null){
						$icon.replaceWith('<img src="'+(data['flag'] ? '<?php echo url('neofrag/themes/default/images/flags/'); ?>'+data['flag'] : '<?php echo url('neofrag/themes/default/images/icons/user-silhouette-question.png'); ?>')+'" data-toggle="tooltip" title="'+data['location']+'" style="margin-right: 10px;" alt="" />');
					}
					else {
						$icon.replaceWith('');
					}
				}
			});
		});
	};
	
	$('body').on('nf.table.load', geolocalisation);
	
	geolocalisation();
});