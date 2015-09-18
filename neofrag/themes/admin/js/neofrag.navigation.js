$(function(){
	$('[data-toggle="sidebar"]').click(function(){
		$('.navbar-default.sidebar').toggleClass('toggle');
		$('#page-wrapper').toggleClass('fullwidth');
		return false;
	});
	
	$('#side-menu > li > a').click(function(e){
		if (window.innerWidth < 768){
			e.stopImmediatePropagation();
			var $subnav = $(this).next('ul.nav.nav-second-level.collapse').toggle();
			$('ul.nav.nav-second-level.collapse').filter(function(){
				return $subnav[0] != $(this)[0];
			}).hide();
			return !$subnav.length;
		}
	});
	
	$(document).click(function(){
		if (window.innerWidth < 768){
			$('ul.nav.nav-second-level.collapse').hide();
		}
	});
	
	$('#side-menu').metisMenu();

	var slideout = new Slideout({
		'panel':     $('#page-wrapper')[0],
		'menu':      $('.navbar-default.sidebar')[0],
		'duration':  150,
		'padding':   53,
		'tolerance': 70
	});
	
	$('.touch-menu').click(function(){
		slideout.toggle();
		return false;
	});
});