$(function(){
	$('.switch > .btn').click(function(){
		$.post('<?php echo url('admin/ajax/settings/maintenance.json') ?>', {closed: $(this)[0] === $('.switch > .btn:last-child')[0] ? 1 : 0}, function(data){
			if (data.status){
				$('.switch > .btn:first-child').removeClass('btn-success').addClass('btn-light').find('i.fa').removeClass('fas fa-toggle-on').addClass('fas fa-toggle-off');
				$('.switch > .btn:last-child').removeClass('btn-light').addClass('btn-danger').find('i.fa').removeClass('fas fa-toggle-off').addClass('fas fa-toggle-on');
			}
			else {
				$('.switch > .btn:first-child').removeClass('btn-light').addClass('btn-success').find('i.fa').removeClass('fas fa-toggle-off').addClass('fas fa-toggle-on');
				$('.switch > .btn:last-child').removeClass('btn-danger').addClass('btn-light').find('i.fa').removeClass('fas fa-toggle-on').addClass('fas fa-toggle-off');
			}
		});

		return false;
	});
});
