$(function(){
	$('body').on('mouseenter', '.user-profile', function(){
		var $this    = $(this);
		var user_id  = $this.data('user-id');
		var username = $this.data('username');
		
		if ($this.data('bs.popover') !== undefined){
			$this.popover('show');
		}
		else {
			var $profile = $('.user-profile-cache[data-user-id="'+user_id+'"]');
			
			if ($profile.length){
				$this.popover({
					content: $profile.html(),
					container: 'body',
					html: true
				}).popover('show');
			}
			else {
				$this.on('remove', function(){
					$this.popover('hide');
				}).data('ajax', $.get('<?php echo url('ajax/user/'); ?>'+user_id+'/'+username, function(data){
					$('<div data-user-id="'+user_id+'"/>')	.hide()
															.addClass('user-profile-cache')
															.html(data)
															.appendTo('body');
								
					$this.popover({
						content: data,
						container: 'body',
						html: true
					}).popover('show');
				}));
			}
		}
	});
	
	$('body').on('mouseleave', '.user-profile', function(){
		var ajax = $(this).data('ajax');
		
		if (ajax){
			ajax.abort();
		}

		$(this).popover('hide');
	});
});