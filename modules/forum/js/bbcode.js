$(function(){
	$(".spoiler-trigger").click(function() {
		$(this).parent().next().collapse('toggle');
	});	
});