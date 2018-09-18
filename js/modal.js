var modals = [];

var modal = function(url){
	var show = function(){
		$('.modal.show').modal('hide');
		modals[url].modal();
	};

	if (typeof modals[url] == 'undefined'){
		$.ajax({
			url: url,
			cache: false,
			success: function(data){
				if (typeof data.success != 'undefined' && data.success == 'refresh'){
					location.reload();
					return;
				}

				if (typeof data.css != 'undefined'){
					$('head').append(data.css);
				}

				if (typeof data.js != 'undefined'){
					var scripts = [];

					$('script').each(function(){
						var src = $(this).attr('src');

						if (typeof src != 'undefined'){
							scripts.push(src);
						}
					});

					$.each(data.js, function(_, js){
						if ($.inArray(js, scripts) == -1){
							$.getScript(js);
						}
					});
				}

				var $modal = $(data.content).appendTo('body').closest('.modal');

				$modal.on('submit', 'form', function(e){
					e.preventDefault();

					var $submit = $modal.find('[type="submit"]');

					if ($submit.hasClass('disabled'))
					{
						return;
					}

					$submit.addClass('disabled');

					var form = $modal.find('form')[0];

					$.ajax({
						url: form.action,
						type: form.method,
						data: new FormData(form),
						processData: false,
						contentType: false,
						success: function(data){
							if (typeof data.success != 'undefined' && data.success == 'refresh'){
								location.reload();
								return;
							}

							if (typeof data.redirect != 'undefined'){
								window.location.href = data.redirect;
								return;
							}

							$submit.removeClass('disabled');

							if (typeof data.form != 'undefined'){
								$modal.find('.modal-body').html(data.form);
								$('body').trigger('nf.load');
							}

							if (typeof data.modal != 'undefined' && data.modal == 'dispose'){
								//TODO modal('dispose') doesn't work?
								$modal.modal('hide').on('hidden.bs.modal', function(){
									$modal.remove();
									delete modals[url];
								});
							}

							if (typeof data.notify != 'undefined'){
								$.each(data.notify, function(_, n){
									notify(n.message, n.type);
								});
							}
						}
					});
				});

				$('body').trigger('nf.load');

				modals[url] = $modal;

				show();
			}
		});
	}
	else {
		show();
	}
};

$(function(){
	$(document).on('click', '[data-modal-ajax]', function(e){
		modal($(this).data('modal-ajax'));
		e.preventDefault();
	});
});
