$(function(){
	var close = function($menu, $parent){
		$menu.next('.nav').slideUp(function(){
			$parent.removeClass('active');
		});
	};

	$('.nav .nav-link[data-toggle="collapse"]').each(function(){
		var $menu   = $(this);
		var $parent = $menu.parent();

		$menu.on('click', function(){
			if ($parent.hasClass('active')){
				close($menu, $parent);
			}
			else {
				$menu.next('.nav').slideDown(function(){
					$parent.addClass('active');
				});

				$menu.parents('.nav').find('.nav-link[data-toggle="collapse"]').each(function(){
					if ($menu[0] != $(this)[0]){
						close($(this), $(this).parent());
					}
				});
			}
		});
	});
});
