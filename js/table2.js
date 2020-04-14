$(function(){
	var request = {};

	$('body').on('click', '.table th > a', function(e){
		var $col     = $(this);
		var $table   = $col.parents('.panel-table:first');
		var table_id = $table.data('id');

		if (request[table_id] != null){
			request[table_id].abort();
		}

		var data = {
			id: table_id,
			sort:     $col.data('col')
		};

		if (e.shiftKey){
			data.action = 'append';
		}
		else if (e.ctrlKey){
			data.action = 'drop';
		}

		request[table_id] = $.ajax({
			url: window.location.pathname,
			type: 'POST',
			data: {
				table2: data
			},
			dataType: 'json',
			success: function(data){
				$table.find('.table').html(data.content);
			}
		});

		return false;
	});
});
