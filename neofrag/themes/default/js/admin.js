$('a[data-toggle="tab"]').on('shown.bs.tab', function(){
	$('.tab-content').parents('.panel:first').find('.panel-heading > h3.panel-title').html($(this).html());
})