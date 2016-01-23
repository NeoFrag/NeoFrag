$(function(){
	var sortable = function(){
		$('.btn-sortable').each(function(){
			var $btn = $(this);
			$btn.parents($btn.data('parent')+':first').sortable({
				axis: 'y',
				cursor: 'move',
				intersect: 'pointer',
				items: $btn.data('items'),
				opacity: 0.6,
				revert: true,
				update: function(event, ui){
					$.post($btn.data('update'), {
						id: $(ui.item).find('.btn-sortable:first').data('id'),
						position: $(this).find($btn.data('items')).index(ui.item)
					});
				}
			});
		});
	};
	
	$('body').on('nf.load', sortable);
	
	sortable();
});