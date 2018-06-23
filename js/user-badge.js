$(function(){
	var close = function($element){
		setTimeout(function(){
			$element.data('hover', $element.data('hover') - 1);

			if (!$element.data('hover')){
				$element.popover('hide');
			}
		}, 125);
	};

	$('body').on('mouseenter', '.user-badge', function(){
		var $this    = $(this);
		var user_id  = $this.data('user-id');
		var username = $this.data('username');

		if (typeof $this.data('hover') == 'undefined'){
			$this.data('hover', 0);
		}

		$this.data('hover', $this.data('hover') + 1);

		if ($this.data('bs.popover') !== undefined){
			$this.popover('show');
		}
		else {
			$this.on('remove', function(){
				$this.popover('hide');
			}).data('ajax', $.get('<?php echo url('ajax/user') ?>/'+user_id+'/'+username, function(data){
				$('.user-badge[data-user-id="'+user_id+'"]').each(function(){
					$(this).popover({
						content: data,
						placement: 'auto',
						container: 'body',
						html: true
					}).on('inserted.bs.popover', function(e){
						var $this = $(this);

						$($this.data('bs.popover').tip).on('mouseenter', function(){
							$this.data('hover', $this.data('hover') + 1);
						}).on('mouseleave', function(){
							close($this);
						});

						$(this).off(e);
					});
				});

				$this.popover('show');
			}));
		}
	});

	$('body').on('mouseleave', '.user-badge', function(){
		var $this = $(this);
		var ajax  = $this.data('ajax');

		if (ajax){
			ajax.abort();
		}

		close($this);
	});
});
