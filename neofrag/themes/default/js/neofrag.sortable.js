$(function(){
	$('.btn-sortable').each(function(){
		var $btn = $(this);
		var $table = $btn.parents('table:first').sortable({
			axis: 'y',
			cursor: 'move',
			intersect: 'pointer',
			items: 'tr',
			opacity: 0.6,
			revert: true,
			update: function(event, ui){
				$.post($btn.data('update'), {
					id: $(ui.item).find('.btn-sortable:first').data('id'),
					position: $(this).find('tr').index(ui.item)
				});
			}
		});
	});
});