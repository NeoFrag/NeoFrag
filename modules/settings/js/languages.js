$(function(){
	$('body').on('click', '.languages [data-language]', function(){
		$.post('<?php echo url('ajax/settings/languages') ?>', {
			'url': window.location.pathname+window.location.search+window.location.hash,
			'language': $(this).data('language')
		}, function(data){
			if (typeof data.redirect != 'undefined'){
				window.location.href = data.redirect;
			} else {
				$('.modal.show').modal('hide');
			}
		});

		return false;
	});
});
