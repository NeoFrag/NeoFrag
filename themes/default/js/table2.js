$(function(){
	var request = {};

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

	$('body').on('keyup', '.table-search', function(){
		var $input = $(this);
		var $panel = $input.parents('.panel-table:first');
		var id     = $panel.data('id');

		if (request[id] != null){
			request[id].abort();
		}

		if (!$input.next('.form-control-feedback').length){
			$input.after('<span class="form-control-feedback" style="background: url(<?php echo image('ajax-loader.gif') ?>) 50% 50% no-repeat;"></span>');
		}

		request[id] = $.ajax({
			url: $panel.data('ajax-url') ? $panel.data('ajax-url') : window.location.pathname,
			type: 'POST',
			data: ($panel.data('ajax-post') ? $panel.data('ajax-post')+'&' : '')+'search='+$input.val()+'&_='+id,
			dataType: 'json',
		}).done(function(data){
			var $table  = $panel.find('.table');
			var $footer = $panel.find('.panel-footer');

			if (typeof data.table != 'undefined'){
				$table.show().html(data.table).next('.panel-body').remove();
				$('body').trigger('nf.load');
			}
			else if ($table.is(':visible')) {
				$table.hide().after('<div class="panel-body"><?php echo $this->lang('no_result') ?></div>');
			}

			if (typeof data.footer != 'undefined'){
				$footer.show().html(data.footer);
			}
			else {
				$footer.hide();
			}
		}).always(function(){
			$input.next('.form-control-feedback').remove();
		});
	});
});
