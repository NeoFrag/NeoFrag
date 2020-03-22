var modal = new function(){
	var _modals = {};
	var _scripts;

	this.exec = function(callback){
		return function(data){
			if (typeof data.success != 'undefined' && data.success == 'refresh'){
				location.reload();
				return;
			}

			if (typeof data.redirect != 'undefined'){
				window.location.href = data.redirect;
				return;
			}

			if (typeof data.css != 'undefined'){
				$('head').append(data.css);
			}

			var promises = [];

			if (typeof data.js != 'undefined'){
				if (typeof _scripts == 'undefined'){
					_scripts = [];

					$('script').each(function(){
						var src = $(this).attr('src');

						if (typeof src != 'undefined'){
							_scripts.push(src);
						}
					});
				}

				$.each(data.js, function(_, js){
					if ($.inArray(js, _scripts) == -1){
						var d = $.Deferred();

						$.when.apply($, promises).then(function(){
							$.getScript(js).then(function(){
								_scripts.push(js);
								d.resolve();
							});
						});

						promises.push(d);
					}
				});
			}

			if (typeof data.notify != 'undefined'){
				$.each(data.notify, function(_, n){
					notify(n.message, n.type);
				});
			}

			$.when.apply($, promises).then(function(){
				callback(data);
			});
		};
	};

	this.load = function(url){
		var show = function(){
			$('.modal.show').modal('hide');
			_modals[url].modal();
		};

		if (typeof _modals[url] == 'undefined'){
			$.ajax({
				url: url,
				cache: false,
				success: this.exec(function(data){
					if (typeof data.content != 'undefined'){
						var $modal = _modals[url] = $(data.content).appendTo('body').closest('.modal');

						$('body').trigger('nf.load');

						var $form = $modal.find('form');

						if (typeof form != 'undefined' && $form.length){
							$modal.on('submit', 'form', function(e){
								e.preventDefault();

								var $submit = $modal.find('[type="submit"]');

								if ($submit.hasClass('disabled')){
									return;
								}

								$submit.addClass('disabled');

								form.submit($form).then(function(data){
									$submit.removeClass('disabled');

									if (typeof data.modal != 'undefined' && data.modal == 'dispose'){
										//TODO modal('dispose') doesn't work?
										$modal.modal('hide').on('hidden.bs.modal', function(){
											$modal.remove();
											delete _modals[url];
										});
									}
								});
							});

							form.load($form);
						}

						show();
					}
				})
			});
		}
		else {
			show();
		}
	};

	return this;
};

$(function(){
	$(document).on('click', '[data-modal-ajax]', function(e){
		modal.load($(this).data('modal-ajax'));
		e.preventDefault();
	});
});
