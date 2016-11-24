$(function(){
	$('.switch > .btn').click(function(){
		$.post('<?php echo url('admin/ajax/settings/maintenance.json'); ?>', {closed: $(this)[0] === $('.switch > .btn:last-child')[0] ? 1 : 0}, function(data){
			if (data.status){
				$('.switch > .btn:first-child').removeClass('btn-success').addClass('btn-default').find('i.fa').removeClass('fa-toggle-on').addClass('fa-toggle-off');
				$('.switch > .btn:last-child').removeClass('btn-default').addClass('btn-danger').find('i.fa').removeClass('fa-toggle-off').addClass('fa-toggle-on');
			}
			else {
				$('.switch > .btn:first-child').removeClass('btn-default').addClass('btn-success').find('i.fa').removeClass('fa-toggle-off').addClass('fa-toggle-on');
				$('.switch > .btn:last-child').removeClass('btn-danger').addClass('btn-default').find('i.fa').removeClass('fa-toggle-on').addClass('fa-toggle-off');
			}
		});
		
		return false;
	});
});