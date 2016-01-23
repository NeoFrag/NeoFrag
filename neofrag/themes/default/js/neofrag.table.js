$(function(){
	var request = {};
	
	$('body').on('change', 'th > input[type="checkbox"].table-checkbox', function(){
		$('td > input[type="checkbox"].table-checkbox').prop('checked', $(this).is(':checked'));
		$(this).data('original-title', ($(this).is(':checked')) ? 'Désélectionner tout' : 'Sélectionner toutes les lignes').tooltip('show');
	});

	$('body').on('change', checkbox = 'td > input[type="checkbox"].table-checkbox', function(){
		$('th > input[type="checkbox"].table-checkbox')
			.prop('checked', $(checkbox).length == $(checkbox+':checked').length)
			.data('original-title', ($(this).is(':checked')) ? 'Désélectionner tout' : 'Sélectionner toutes les lignes');
	});
	
	$('body').on('click', '.table thead .sort', function(){
		var $col     = $(this);
		var $table   = $col.parents('.table-area:first');
		var table_id = $table.data('table-id');
		
		if (request[table_id] != null){
			request[table_id].abort();
		}
		
		request[table_id] = $.ajax({
			url: $table.data('ajax-url') ? $table.data('ajax-url') : window.location.pathname,
			type: 'POST',
			data: ($table.data('ajax-post') ? $table.data('ajax-post')+'&' : '')+'sort=['+$col.data('column')+',"'+$col.data('order-by')+'"]&table_id='+table_id,
			dataType: 'json',
			success: function(data){
				$table.find('.table-content').html(data.content);
				$('body').trigger('nf.load');
			}
		});
	});
	
	$('body').on('keyup', '.table-search input', function(){
		var $input   = $(this);
		var $table   = $input.parents('.table-area:first');
		var table_id = $table.data('table-id');
		
		if (request[table_id] != null){
			request[table_id].abort();
		}
		
		if (!$input.next('.form-control-feedback').length){
			$input.after('<span class="form-control-feedback" style="background: url(<?php echo image('ajax-loader.gif'); ?>) 50% 50% no-repeat;"></span>');
		}
		
		request[table_id] = $.ajax({
			url: $table.data('ajax-url') ? $table.data('ajax-url') : window.location.pathname,
			type: 'POST',
			data: ($table.data('ajax-post') ? $table.data('ajax-post')+'&' : '')+'search='+$input.val()+'&table_id='+table_id,
			dataType: 'json',
			success: function(data){
				/*input.typeahead().data('typeahead').source = data.search;*/ //TODO
				
				$table.find('.table-content').html(data.content);
				$input.next('.form-control-feedback').remove();
			
				$('body').trigger('nf.load');
			}
		});
	});
});