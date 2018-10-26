$(function(){
	$('.list-group-item').on('click', function(){
		$('.list-group-item, .tab-content .tab-pane').removeClass('active');
		$(this).addClass('active');
		$('.tab-content .tab-pane[data-tab="'+$(this).attr('href').replace('#', '')+'"]').addClass('active');
		$('.tab-content').parents('.card:first').find('h6.card-header').html($(this).html());
	});

	var hashchange = function(){
		var $item = $('[href="'+window.location.hash+'"]');

		$($item.length ? $item : $('.list-group-item:first')).trigger('click');
	};

	$(window).on('hashchange', hashchange);

	hashchange();
});
