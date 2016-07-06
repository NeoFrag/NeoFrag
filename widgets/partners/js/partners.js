$(function(){
	var resize_slider = function() {
		$('.partner-item img.logo').each(function() {
			$(this).animate({
				'max-width':$(this).parent().parent().outerWidth() - 20 + 'px',
				'max-height':$(this).parent().parent().outerHeight() - 30 + 'px',
			}, 500);

			$(this).stop(true, true).delay(100).animate({
				opacity: 0.7
			});
		});
	}
	
	resize_slider();

	$('.carousel.slide').on('slid.bs.carousel', function() {
		resize_slider();
	});

	$('.column-partners img.logo').each(function() {
		$(this).animate({'max-width':$(this).parent().parent().outerWidth() - 20 + 'px'}, 500);

		$(this).stop(true, true).delay(100).animate({
			opacity: 0.7
		});
	});

	$('.partner-item img.logo, .column-partners img.logo').hover(
		function() {
			$(this).animate({opacity: 1});
		}, function() {
			$(this).animate({opacity: 0.7});
		}
	);
});
