$(function(){
	var updating = false;

	var update = function(){
		if (updating){
			return;
		}

		updating = true;

		var data = {};

		$.each($('form').serializeArray(), function(){
			if (data[this.name] !== undefined){
				if (!data[this.name].push){
					data[this.name] = [data[this.name]];
				}

				data[this.name].push(this.value || '');
			}
			else {
				data[this.name] = this.value || '';
			}
		});

		$.post('<?php echo url('admin/ajax/statistics.json'); ?>', data, function(series){
			$('#highcharts').highcharts('StockChart', {
				chart: {
					height: 750,
					zoomType: 'x'
				},
				title: {
					text: null
				},
				xAxis: {
					type: 'datetime'
				},
				yAxis: {
					title: {
						text: null
					}
				},
				legend: {
					enabled: false
				},
				credits: {
					enabled: false
				},
				series: series
			});
		}).always(function(){
			updating = false;
		});
	};
	
	update();
	$('form input, form select, .date').on('change changeDate dp.change', update);
});