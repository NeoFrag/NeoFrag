$(function(){
	$('#language ul li').append('<i></i>').children('i').addClass('glyphicon glyphicon-chevron-left');
	
	$('#language ul li:first-child').children('i').addClass('glyphicon glyphicon-chevron-down');

	$('#language ul li:first-child').mouseover(function(){
		$(this).nextAll('li').each(function(){
			$(this).fadeIn()
		});
	});
	
	$('#language ul').mouseleave(function(){
		$(this).children('li').nextAll('li').each(function(){
			$(this).fadeOut()
		});
	});
	
	$('#language ul li').click(function(){
		$.ajax({
		  url: '{base_url}ajax/settings/language.html',
		  type: 'POST',
		  data: 'language='+$(this).data('lang')
		}).done(function(){
			location.reload(true);
		});
	});
});