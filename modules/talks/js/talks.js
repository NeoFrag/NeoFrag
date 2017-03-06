$(function(){
	var update = function($talks, olders){
		if ($talks.data('onupdate')){
			return;
		}
		
		if (typeof olders == 'undefined'){
			olders = false
		}
		
		var $first = $talks.find('[data-message-id]:last');
		var data = {
			talk_id:    $talks.data('talk-id'),
			message_id: $first.length ? $first.data('message-id') : 0
		};

		if (olders){
			$.extend(data, {
				position: $first.length ? $first.data('position') : 0
			});
		}
		
		$talks.data('onupdate', true);
		
		$.post('<?php echo url('ajax/talks'); ?>'+(olders ? '/older' : ''), data, function(data){
			if (olders){
				$talks.append(data);
			}
			else {
				$talks.html(data);
			}
		}).always(function(){
			$talks.data('onupdate', false);
		});
	};
	
	var refresh = function(){
		$('.widget.widget-talks [data-talk-id]').each(function(){
			update($(this));
		});
		
		setTimeout(refresh, 10000);
	};
	
	refresh();
	
	$('.widget.widget-talks .panel-body').mCustomScrollbar({
		theme: 'dark',
		callbacks: {
			onScroll:function(){
				if (this.mcs.topPct >= 97){
					update($(this).find('[data-talk-id]:first'), true);
				}
			}
		}
	});
	
	$('.widget.widget-talks .panel-footer form').submit(function(){
		var $input = $(this).find('input[type="text"]');
		var $talks = $(this).parents('.widget.widget-talks:first').find('[data-talk-id]:first');
		
		if ($input.val()){
			$.post('<?php echo url('ajax/talks/add_message'); ?>', {
				talk_id: $talks.data('talk-id'),
				message: $input.val()
			}, function(data){
				$input.val('');
				update($talks);
			});
		}
		
		return false;
	});
});