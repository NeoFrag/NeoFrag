$(function(){
	$('section:first').fadeIn();

	var delay = function(delay){
		var d = $.Deferred();
		setTimeout(function(){
			d.resolve();
		}, delay);
		return d;
	};

	var icon = function(icon){
		var icons = {
			danger:  'fas fa-times',
			success: 'fas fa-check-circle',
			info:    'fas fa-info-circle',
			warning: 'far fa-exclamation-triangle'
		};

		return '<i class="icon fa '+icons[icon]+' text-'+icon+' fa-fw"></i>';
	};

	var next = function(){
		var $section = $('section:visible');
		$section.find('[data-action="next-step"]').hide();
		$section.fadeOut(function(){
			$(this).next().fadeIn();
		});
	};

	if ($('.check-init').length){
		$.ajax({
			url: 'index.php?step=check',
			success: function(data){
				var d = $.Deferred().resolve();
				var errors = 0;
				var all = [];

				$.each(data, function(_, data){
					var d2 = $.Deferred();
					all.push(d2);

					d = d.then(function(){
						var $check = $('.first-check-errors .list-group-item.d-none');

						var $new = $check.clone().removeClass('d-none').hide();
						$new.html($new.html()
							.replace('{icon}',  '<i class="icon fa fa-circle-notch fa-spin fa-fw"></i>')
							.replace('{title}', data.title)
						);

						$check.before($new.fadeIn());

						$('.first-check-errors').removeClass('d-none');

						return delay(300).then(function(){
							setTimeout(function(){
								var $info = $new.find('.list-inline').hide();

								$new.find('.icon').fadeOut(function(){
									if (data.icon == 'danger'){
										$new.addClass('text-danger');
										errors++;
									}

									$(this).attr('class', $(icon(data.icon)).attr('class')).fadeIn();

									$.each(data.info, function(a, b){
										$info.append('<li class="list-inline-item">'+(isNaN(parseInt(a)) ? a+' ' : '')+'<b>'+b+'</b></li>');
									});

									$info.fadeIn(function(){
										d2.resolve();
									});
								});
							}, Math.random() * 6000 + 1500);
						});
					});
				});

				$.when.apply($, all).then(function(){
					if (errors){
						$('section:visible .legend .checking').fadeOut(function(){
							$('section:visible .legend .errors').hide().removeClass('d-none').fadeIn();
						});
					}
					else{
						$('section:visible .legend').fadeTo(400, 0, function(){
							$(this).addClass('invisible');
						});
						$('section:visible .btn-action').hide().removeClass('invisible').fadeIn();
					}
				});
			}
		});
	}

	var check_form = function($form, hidden, install){
		var $next_btn = $('section:visible .btn-action');
		var $btn      = $form.find('button[type="submit"]');
		var step      = $form.parents('.step:first').data('step');
		var d;

		if (!hidden){
			$form.find('.form-control').each(function(){
				$(this).removeClass('is-valid is-invalid').siblings('.invalid-feedback').remove();
			});

			$next_btn.hide().removeClass('invisible').fadeOut();

			$btn.data('original-text', $btn.text()).html('<i class="fas fa-circle-notch fa-spin"></i> '+$btn.data('loading-text')).prop('disabled', true);

			d = delay(750);
		}

		var d2 = $.ajax({
			url: 'index.php?step='+step+(install ? '&install=true' : ''),
			type: 'POST',
			data: $form.serialize()
		});

		var valid_input = function($input){
			if ($input.val() != ''){
				$input.addClass('is-valid');
			}
		};

		var d3 = $.Deferred();

		$.when(d, d2).done(function(_, data){
			if (data[0] == 'ok'){
				$next_btn.fadeIn();

				$form.find('.form-control').each(function(){
					valid_input($(this));
				});

				d3.resolve();

				if (step == 'user'){
					next();
					return;
				}
			}
			else if (typeof data[0].errors != 'undefined'){
				$next_btn.fadeOut();

				$.each(data[0].errors, function(name, value){
					var $input = $form.find('.form-control[name='+name+']');

					if (value == 'ok'){
						valid_input($input);
					}
					else {
						if (value != ''){
							$input.after('<div class="invalid-feedback">'+value+'</div>');
						}

						$input.addClass('is-invalid');
					}
				});
			}

			$btn.html($btn.data('original-text')).prop('disabled', false);
		});

		return d3;
	};

	$('.step[data-step] form').submit(function(e){
		e.preventDefault();
		check_form($(this), false, false);
	});

	$('body').on('click', '[data-action="next-step"]', function(){
		if ($(this).parents('.step:first').data('step') == 'db'){
			var $form = $('section:visible').find('form');

			check_form($form, true, false).then(function(){
				next();

				var blinking = function($elem){
					if ($elem.length){
						$elem.fadeIn(1500, function(){
							$elem.fadeOut(1500, function(){
								blinking($elem);
							});
						});
					}
				};

				delay(1000).then(function(){
					blinking($('.blinking'))
				});

				$.when(delay(5000), check_form($form, true, true)).done(function(){
					document.location.reload();
				});
			});
		}
		else {
			next();
		}
	});
});
