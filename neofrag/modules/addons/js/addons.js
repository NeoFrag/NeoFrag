$(function(){
	var load_addons = function($addon){
		$.ajax({
			url: '<?php echo url('admin/ajax/addons.html'); ?>',
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
				if (typeof data.success != 'undefined'){
					notify(data.success);
					hashChange();
				}
			}
		});
		
		return false;
	});
	
	//Install
	$('input[type="file"].install').change(function(){
		if ($(this).val()){
			$('#install-input-label i.fa:first').removeClass('fa-upload').addClass('fa-check text-green');
			$('#install-input-label span.legend').html('Archive sélectionnée');
			$('.btn.install').removeClass('disabled');
		}
		else{
			$('#install-input-label i.fa:first').removeClass('fa-check text-green').addClass('fa-upload');
			$('#install-input-label span.legend').html('Choisissez votre archive');
			$('.btn.install').addClass('disabled');
		}
	});
	
	$('.btn.install').click(function(){
		var formData = new FormData();
		formData.append('file', $('input[type="file"].install')[0].files[0]);
		
		$('input[type="file"].install').val('').trigger('change');
		
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
				
				hashChange();
			}
		});
		
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

			modal_theme('<?php echo i18n('theme_activation'); ?>', '<?php echo i18n('theme_activation_message'); ?>', '<button type="button" class="btn btn-info" data-theme="'+theme_name+'"><?php echo i18n('activate'); ?></button>', function(){
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
		modal_theme('<?php echo i18n('reinstall_to_default'); ?>', '<?php echo i18n('theme_reinstallation_message'); ?>', '<button type="button" class="btn btn-warning" data-theme="'+$(this).parents('.thumbnail:first').data('theme')+'"><?php echo i18n('reinstall'); ?></button>', function(){
			$.post('<?php echo url('admin/ajax/addons/theme/reset.json'); ?>', {theme: $(this).data('theme')}, function(data){
				notify(data.success);
			});
		});
	});
});