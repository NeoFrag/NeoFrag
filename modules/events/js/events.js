$(function(){
	$('#calendar').fullCalendar({
		height: 350,
		locale: '<?php echo NeoFrag()->config->lang->info()->name ?>',
		eventSources: ['<?php echo url('ajax/events.json') ?>'],
		eventRender: function(event, element){
			element.find('.fc-content').prepend('<i class="icon '+event.icon+' fa-fw"></i>');

			element.on('mouseenter', function(){
				if (element.data('bs.popover') !== undefined){
					element.popover('show');
				}
				else {
					var $event = $('.event-cache[data-event-id="'+event.id+'"]');

					if ($event.length){
						element.popover({
							content: $event.html(),
							container: 'body',
							html: true
						}).popover('show');
					}
					else {
						element.on('remove', function(){
							element.popover('hide');
						}).data('ajax', $.get('<?php echo url('ajax/events') ?>/'+event.id+'/'+event.url_title+'', function(data){
							$('<div data-event-id="'+event.id+'"/>').hide()
																	.addClass('event-cache')
																	.html(data)
																	.appendTo('body');

							element.popover({
								content: data,
								container: 'body',
								html: true
							}).popover('show');
						}));
					}
				}
			});

			element.on('mouseleave', function(){
				var ajax = element.data('ajax');

				if (ajax){
					ajax.abort();
				}

				element.popover('hide');
			});
		}
	});
});
