var $gallery = $('#carousel-gallery');
var $bar     = $('.transition-timer-carousel-progress-bar');
var percent  = 0;

$gallery.carousel({
	interval: false,
	pause: true
}).on('slid.bs.carousel', function (){percent=0;});

var barInterval = setInterval(progressBarCarousel, 30);

function progressBarCarousel(){
	$bar.css({width:percent+'%'});
	percent = percent + 0.5;
	if (percent > 100) {
		percent = 0;
		$gallery.carousel('next');
	}
}

$gallery.hover(
	function(){
		clearInterval(barInterval);
		$('#pauseButton').toggleClass('active');
		$('#playButton').removeClass('active');
	},
	function(){
		barInterval = setInterval(progressBarCarousel, 30);
		$('#pauseButton').removeClass('active');
		$('#playButton').toggleClass('active');
	}
);

$('#playButton').on('click', function (){
	$('#pauseButton').removeClass('active');
	$('#playButton').addClass('active');
	barInterval = setInterval(progressBarCarousel, 30);
	$gallery.carousel('cycle');
});

$('#pauseButton').on('click', function (){
	$('#pauseButton').addClass('active');
	$('#playButton').removeClass('active');
	clearInterval(barInterval);
	$gallery.carousel('pause');
});

//TODO

/*
$gallery.on('slide.bs.carousel', function (event) {
	var c_height = $(event.relatedTarget).height();
	var c_maxHeight = $(window).height() - $('#modalGallery .modal-header:first').outerHeight() - $('#modalGallery .modal-footer:first').outerHeight();

	if (c_height >= c_maxHeight) {
		$('#modalGallery .item.active img').animate({
			height: c_maxHeight
		});
		$(event.relatedTarget).parent().parent().animate({
			marginTop: $('#modalGallery .modal-header:first').outerHeight()+'px',
			height: c_maxHeight
		});
		
		console.log('hauteur: '+c_height+'; Limite: '+c_maxHeight);
	}
});
*/