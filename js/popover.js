var popover = new function(){
	var _cache = {};

	this.load = function($elem){
		var mouseover = true;

		if (typeof $elem.data('bs.popover') == 'undefined'){
			var url = $elem.data('popover-ajax');

			$elem.on('mouseleave', function(){
				mouseover = false;
				setTimeout(function(){
					if (!mouseover){
						$elem.popover('hide');
					}
				}, 200);
			});

			var d = $.Deferred();

			if (typeof _cache[url] == 'undefined'){
				$.ajax({
					url: url,
					cache: false,
					success: function(data){
						_cache[url] = data;
						d.resolve();
					}
				});
			}
			else {
				d.resolve();
			}

			d.promise().then(function(){
				$elem.popover({
					content:   _cache[url],
					trigger:   'manual',
					placement: 'auto',
					container: 'body',
					sanitize:  false,
					html:      true
				});

				if (mouseover){
					$elem.popover('show');

					$($elem.data('bs.popover').tip).hover(function(){
						mouseover = true;
					}, function(){
						mouseover = false;
						$elem.popover('hide');
					});
				}
			});
		}
		else{
			$elem.popover('show');
		}
	};

	return this;
};

$(function(){
	$(document).on('mouseenter', '[data-popover-ajax]', function(e){
		popover.load($(this));
		e.preventDefault();
	});
});
