$(function(){
	$('#forums-list').sortable({
		axis: 'y',
		cursor: 'move',
		intersect: 'pointer',
		items: '> .panel',
		opacity: 0.6,
		revert: true,
		update: function(event, ui){
			$.post('{base_url}admin/ajax/forum/categories/move.html', {
				category_id: $(ui.item).find('[data-category-id]:first').data('category-id'),
				position: $(this).find('.panel').index(ui.item)
			});
		}
	});
	
	$('.forum-content').sortable({
		axis: 'y',
		connectWith: '.forum-content',
		cursor: 'move',
		intersect: 'pointer',
		items: '> tr[data-forum-id]',
		opacity: 0.6,
		revert: true,
		update: function(event, ui){
			if (this === ui.item.parent()[0]){
				$.post('{base_url}admin/ajax/forum/move.html', {
					category_id: $(ui.item).parents('[data-category-id]:first').data('category-id'),
					forum_id: $(ui.item).data('forum-id'),
					position: $(this).find('tr').index(ui.item)
				});
			}
		}
	});
});