$(function(){
	var load_addons = function($addon){
		$.ajax({
			url: '<?php echo url('admin/ajax/addons'); ?>',
			type: 'POST',
			data: {
				'addon': $addon.data('addon')
			},
			success: function(data){
				var $row  = $('.module-addons > .row:first');
				var $cols = $row.children('[class^="col-"]');
				
				if ($cols.length > 1){
					$cols.last().remove();
				}
				
				$cols.find('.active[data-addon]').removeClass('active');
				
				location.hash = '#'+$addon.data('addon');
				$addon.addClass('active');
				$row.append(data);
				$('body').trigger('nf.load');
			}
		});
		
		return false;
	};
	
	$('[data-addon]').click(function(){
		return $(this).hasClass('active') ? false : load_addons($(this));
	});

	var hashChange = function(){
		var $addon = $('[data-addon="'+(window.location.hash.replace('#', ''))+'"]');
		
		load_addons($addon.length ? $addon : $('[data-addon]:first'));
	};
	
	$(window).on('hashChange', hashChange);
	
	hashChange();
	
	//Activate / Desactivate
	$('body').on('click', '.item-status-switch > a', function(){
		$.ajax({
			url: '<?php echo url('admin/ajax/addons/active.json'); ?>',
			type: 'POST',
			data: {
				type: $(this).parents('[data-type]:first').data('type'),
				name: $(this).parents('[data-name]:first').data('name')
			},
			success: function(data){
				$.each(data, function(type, message){
					notify(message, type);
				});

				if (typeof data.success != 'undefined'){
					hashChange();
				}
			}
		});
		
		return false;
	});
	
	//Install
	$('input[type="file"].install').change(function(){
		if ($(this).val()){
			$('#install-input-label span.legend').html('Archive sélectionnée');
			$('.btn.install').removeClass('disabled');
		}
		else{
			$('#install-input-label span.legend').html('Choisissez votre archive');
			$('.btn.install').addClass('disabled');
		}
	});
	
	$('.btn.install').click(function(){
		if (!$(this).hasClass('disabled')){
			var formData = new FormData();
			formData.append('file', $('input[type="file"].install')[0].files[0]);
			
			$('.btn.install').data('title', $('.btn.install').html());
			$('.btn.install').addClass('disabled').html('<?php echo icon('fa-spinner fa-pulse'); ?> Veuillez patienter')
			
			$.ajax({
				url: '<?php echo url('admin/ajax/addons/install.json'); ?>',
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				success: function(data){
					$.each(data, function(type, message){
						notify(message, type);
					});
					
					$('input[type="file"].install').val('').trigger('change');

					$('.btn.install').html($('.btn.install').data('title'));
					
					hashChange();
				}
			});
		}

		return false;
	});
	
	//Themes
	var modal_theme = function(title, body, btn, callback){
		var $modal = $('.modal-theme');
		var $btn   = $(btn).appendTo($modal.find('.modal-footer'));
		$modal.find('.modal-title').html(title);
		$modal.find('.modal-body').html(body);
		$modal.modal();
		
		$modal.on('hidden.bs.modal', function(){
			$btn.remove();
		});
		
		$btn.on('click', callback);
		$btn.on('click', function(){
			$modal.modal('hide');
		});
	};
	
	//Activation
	$('body').on('click', '.thumbnail', function(){
		if (!$(this).hasClass('panel-primary')){
			var theme_name = $(this).data('theme');

			modal_theme('<?php echo $this->lang('theme_activation'); ?>', '<?php echo $this->lang('theme_activation_message'); ?>', '<button type="button" class="btn btn-info" data-theme="'+theme_name+'"><?php echo $this->lang('activate'); ?></button>', function(){
				$.post('<?php echo url('admin/ajax/addons/theme/active.json'); ?>', {theme: theme_name}, function(data){
					$('.thumbnail .btn-danger.disabled').removeClass('disabled');
					$('.thumbnail.panel-primary').removeClass('panel-primary');
					$('.thumbnail[data-theme="'+theme_name+'"]').addClass('panel-primary').find('.btn-danger').addClass('disabled');
					$('.modal-theme-activation').modal('hide');

					notify(data.success);
				});
			});
		}
		
		return false;
	});
	
	//Customization
	$('body').on('click', '.thumbnail .btn-info', function(e){
		e.stopPropagation();
	});
	
	//Reset
	$('body').on('click', '.thumbnail .btn-warning', function(e){
		e.stopPropagation();
		modal_theme('<?php echo $this->lang('reinstall_to_default'); ?>', '<?php echo $this->lang('theme_reinstallation_message'); ?>', '<button type="button" class="btn btn-warning" data-theme="'+$(this).parents('.thumbnail:first').data('theme')+'"><?php echo $this->lang('reinstall'); ?></button>', function(){
			$.post('<?php echo url('admin/ajax/addons/theme/reset.json'); ?>', {theme: $(this).data('theme')}, function(data){
				notify(data.success);
			});
		});
	});

	//Authenticators
	var modal_authenticator = function(title, body, btn, callback){
		var $modal = $('.modal-theme');
		var $btn   = $(btn).appendTo($modal.find('.modal-footer'));
		$modal.find('.modal-title').html(title);
		$modal.find('.modal-body').html(body);
		$modal.modal();
		
		$modal.on('hidden.bs.modal', function(){
			$btn.remove();
		});
		
		$btn.on('click', callback);
		$btn.on('click', function(){
			$modal.modal('hide');
		});
	};
	
	//Setting authenticators
	$('body').on('click', '[data-type="authenticator"] .btn-warning', function(){
		var $item = $(this).parents('[data-name]:first');
		var name  = $item.data('name');

		$.post('<?php echo url('admin/ajax/addons/authenticator/admin.json'); ?>', {name: name}, function(data){
			var $modal = $('.modal-authenticator');

			$modal.find('.modal-title').html(data.title);

			var body = '<div class="alert alert-info" role="alert">'+data.help+'</div>';

			$.each(data.settings, function(key, value){
				body += '	<div class="form-group">\
								<label for="settings-'+key+'" class="col-sm-3 control-label">'+key+'</label>\
								<div class="col-sm-5">\
									<input type="text" class="form-control" id="settings-'+key+'" name="'+key+'" value="'+value+'" />\
								</div>\
							</div>';
			});

			$.each(data.params, function(key, value){
				body += '	<div class="form-group">\
								<label class="col-sm-3 control-label">'+key+'</label>\
								<div class="col-sm-5">\
									<p class="help-block"><code>'+value+'</code></p>\
								</div>\
							</div>';
			});

			$modal.find('.modal-body').html('<div class="form-horizontal">'+body+'</div>');

			$modal.find('.btn-success').click(function(){
				var settings = {};

				$modal.find('.modal-body input').each(function(){
					settings[$(this).attr('name')] = $(this).val();
				});

				$.post('<?php echo url('admin/ajax/addons/authenticator/update'); ?>', {name: name, settings: settings}, function(data){
					$modal.modal('hide');
					
					$modal.on('hidden.bs.modal', function(){
						hashChange();
					});
				});
			});

			$modal.on('hidden.bs.modal', function(){
				$modal.find('.btn-success').unbind('click');
			});

			$modal.modal();
		});
	});
});