$(document).ready(function() {
	var height = 0;
	var header = 0;
	var slider = 0;
	var avant_contenu = 0;
	var cover = 0;
	var post_contenu = 0;
	var footer = 0;

	if ($('.header').length > 0) {
		header = $('.header').outerHeight(true);
	}

	if ($('#slider').length > 0) {
		slider = $('#slider').outerHeight(true);
	}

	if ($('#avant-contenu').length > 0) {
		avant_contenu = $('#avant-contenu').outerHeight(true);
	}

	if ($('.user-cover').length > 0) {
		cover = $('.user-cover').outerHeight(true);
	}

	if ($('#post-contenu').length > 0) {
		post_contenu = $('#post-contenu').outerHeight(true);
	}

	if ($('.footer').length > 0) {
		footer = $('.footer').outerHeight(true);
	}

	height = (header + slider + avant_contenu + cover + post_contenu + footer);

	$('#contenu').css('min-height', window.innerHeight - height+'px');
});
