$(function(){
	$('#forums-list').sortable({
		axis: 'y',
		cursor: 'move',
		intersect: 'pointer',
		items: '> .panel',
		opacity: 0.6,
		revert: true,
		update: function(event, ui){
			$.post('<?php echo url('admin/ajax/forum/categories/move'); ?>', {
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
				$.post('<?php echo url('admin/ajax/forum/move'); ?>', {
					parent_id: $(ui.item).parents('[data-category-id]:first').data('category-id'),
					forum_id: $(ui.item).data('forum-id'),
					position: $(this).find('tr').index(ui.item)
				});
			}
		}
	});
	
	$('.subforums').sortable({
		axis: 'y',
		connectWith: '.subforums',
		cursor: 'move',
		intersect: 'pointer',
		items: '> li',
		opacity: 0.6,
		revert: true,
		update: function(event, ui){
			if (this === ui.item.parent()[0]){
				$.post('<?php echo url('admin/ajax/forum/move'); ?>', {
					parent_id: $(ui.item).parents('[data-forum-id]:first').data('forum-id'),
					forum_id: $(ui.item).data('forum-id'),
					position: $(this).find('li').index(ui.item)
				});
			}

		}
	});
});