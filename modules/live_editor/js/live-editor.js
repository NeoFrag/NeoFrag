var modal_style = function(title, $element, styles, callback){
	if ($('body').find('.live-editor-modal').length){
		return;
	}

	var $modal = $('\
		<div class="modal live-editor-modal fade" role="dialog">\
			<div class="modal-dialog modal-lg">\
				<div class="modal-content">\
					<div class="modal-header">\
						<h5 class="modal-title"><?php echo icon('fas fa-paint-brush') ?> '+title+'</h5>\
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
					</div>\
					<div class="modal-body">\
						'+$(styles).html()+'\
					</div>\
					<div class="modal-footer">\
						<button type="button" class="btn btn-dark" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
						<button type="button" class="btn btn-info"><?php echo $this->lang('Valider') ?></button>\
					</div>\
				</div>\
			</div>\
		</div>').appendTo('body').data('element', $element).modal();

	var $widget = $element.parents('.widget:first');

	$element.data('previous-style', $widget.data('widget-style'));

	$modal.find('[data-style]').each(function(){
		console.log($(this).data('style'));
		if ($(this).data('style') == $widget.data('widget-style')){
			$(this).addClass('active');
			return false;
		}
	});

	$modal.on('hidden.bs.modal', function(){
		if ($element.data('previous-style') != $widget.data('widget-style')){
			$element.switchClass($element.data('previous-style'), $widget.data('widget-style'), 200);
		}
		$(this).remove();
	});

	$modal.find('.btn-info:first').on('click', function(){
		var style = $element.data('previous-style');
		$widget.data('widget-style', style);
		$modal.modal('hide');
		callback(style);
	});
};

var modal_settings = function(title, settings, callback){
	if ($('body').find('.live-editor-modal').length){
		return;
	}

	var load_settings = function(){
		var widget = $('#live-editor-settings-widget').val();
		var type   = $('#live-editor-settings-type').val();

		if ($('#live-editor-settings').data('widget-id') && $('#live-editor-settings').data('original-widget') == widget && $('#live-editor-settings').data('original-type') == type){
			var data = {
				widget_id: $('#live-editor-settings').data('widget-id')
			};
		}
		else {
			var data = {
				widget: widget,
				type: type
			};
		}

		$('#live-editor-settings').html('');

		$.post('<?php echo url('admin/ajax/live-editor/widget-admin') ?>', data, function(data){
			if (data){
				$('#live-editor-settings').html(data);
			}
		});
	};

	var $modal = $('\
		<div class="modal live-editor-modal fade" role="dialog">\
			<div class="modal-dialog modal-lg">\
				<div class="modal-content">\
					<div class="modal-header">\
						<h5 class="modal-title"><?php echo icon('fas fa-cogs') ?> '+title+'</h5>\
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
					</div>\
					<div class="modal-body">\
						'+settings+'\
					</div>\
					<div class="modal-footer">\
						<button type="button" class="btn btn-dark" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
						<button type="button" class="btn btn-info"><?php echo $this->lang('Valider') ?></button>\
					</div>\
				</div>\
			</div>\
		</div>').appendTo('body');

	$modal.on('change', '#live-editor-settings-widget', function(){
		var $widgets = $(this), count = 0;

		$('#live-editor-settings-type option:selected').prop('selected', false);

		$('#live-editor-settings-type option').each(function(){
			if ($(this).data('widget') == $widgets.val()){
				$(this).show();
				count++;
			}
			else {
				$(this).hide();
			}
		});

		if (count){
			$('#live-editor-settings-type').parents('.form-group:first').show();
			$('#live-editor-settings-type option[data-widget="'+$(this).val()+'"]:first').prop('selected', true);
		}
		else {
			$('#live-editor-settings-type').parents('.form-group:first').hide();
		}

		if ($(this).val() == 'module'){
			$('#live-editor-settings-title').data('value', $('#live-editor-settings-title').val());
			$('#live-editor-settings-title').val('').parents('.form-group:first').hide();
		}
		else {
			if (!$('#live-editor-settings-title').val()){
				var value = $('#live-editor-settings-title').data('value');

				if (value){
					$('#live-editor-settings-title').val(value);
				}
			}

			$('#live-editor-settings-title').parents('.form-group:first').show();
		}

		if (!$modal.find('#live-editor-settings-type option[data-widget="'+$('#live-editor-settings-widget').val()+'"]').length){
			$('#live-editor-settings-type').val('index').parents('.form-group:first').hide();
		}

		load_settings();
	});

	$modal.on('change', '#live-editor-settings-type', function(){
		load_settings();
	});

	$('#live-editor-settings-type').trigger('change');

	$modal.find('#live-editor-settings-form').submit(function(){
		$modal.find('.btn-info:first').trigger('click');
		return false;
	});

	$modal.modal();

	$modal.on('hidden.bs.modal', function(){
		$(this).remove();
	});

	$modal.find('.btn-info:first').on('click', function(){
		$('#live-editor-settings-form').trigger('nf.live-editor-settings.submit');

		$modal.modal('hide');

		var settings = {};

		settings.settings = null;

		$.each($('#live-editor-settings-form').serializeArray(), function(){
			if (settings[this.name] !== undefined){
				if (!settings[this.name].push){
					settings[this.name] = [settings[this.name]];
				}

				settings[this.name].push(this.value || '');
			}
			else {
				settings[this.name] = this.value || '';
			}
		});

		if (typeof settings.title == 'undefined'){
			settings.title = '';
		}

		callback(settings);
	});
};

var modal_fork = function(callback){
	if ($('body').find('.live-editor-modal').length){
		return;
	}

	var $modal = $('\
		<div class="modal live-editor-modal fade" role="dialog">\
			<div class="modal-dialog">\
				<div class="modal-content">\
					<div class="modal-header">\
						<h5 class="modal-title"><?php echo $this->lang('Revenir à la disposition commune') ?></h5>\
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
					</div>\
					<div class="modal-body">\
						<?php echo $this->lang('Êtes-vous sûr(e) de vouloir revenir à la disposition commune ?<br />Toutes les <b>colonnes</b> et <b>widgets</b> associés à cette zone seront perdus.') ?>\
					</div>\
					<div class="modal-footer">\
						<button type="button" class="btn btn-dark" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
						<button type="button" class="btn btn-danger"><?php echo $this->lang('Continuer') ?></button>\
					</div>\
				</div>\
			</div>\
		</div>').appendTo('body').modal();

	$modal.on('hidden.bs.modal', function(){
		$(this).remove();
	});

	$modal.find('.btn-danger:first').on('click', function(){
		$modal.modal('hide');
		callback();
	});
};

var modal_delete = function(message, callback){
	if ($('body').find('.live-editor-modal').length){
		return;
	}

	var $modal = $('\
		<div class="modal live-editor-modal fade" role="dialog">\
			<div class="modal-dialog">\
				<div class="modal-content">\
					<div class="modal-header">\
						<h5 class="modal-title"><?php echo icon('far fa-trash-alt').' '.$this->lang('Confirmation de suppression') ?></h5>\
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
					</div>\
					<div class="modal-body">\
						'+message+'\
					</div>\
					<div class="modal-footer">\
						<button type="button" class="btn btn-dark" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
						<button type="button" class="btn btn-danger"><?php echo icon('far fa-trash-alt') ?> <?php echo $this->lang('Supprimer') ?></button>\
					</div>\
				</div>\
			</div>\
		</div>').appendTo('body').modal();

	$modal.on('hidden.bs.modal', function(){
		$(this).remove();
	});

	$modal.find('.btn-danger:first').on('click', function(){
		$modal.modal('hide');
		callback();
	});
};

$(function(){
	var $widgets = $('[data-mode="<?php echo \NF\NeoFrag\Core\Output::WIDGETS ?>"]');

	$('form[target="live-editor-iframe"]').submit();

	$('.live-editor-screen[data-width]').click(function(){
		var width = $(this).data('width');

		if (width == '100%'){
			var size = '20px';
			width = 'calc('+width+' - 40px)';
		}
		else {
			var size = 'calc(50% - '+width+' / 2)';
		}

		$('.live-editor-iframe').width(width).css('left', size);
		$('.live-editor-screen').removeClass('active');
		$(this).addClass('active');
		$('#navbarDropdownScreen').html($(this).html()+' <?php echo icon('fas fa-angle-down') ?>');
	});

	$('.live-editor-mode').click(function(){
		$(this).toggleClass('active');

		if ($(this).data('mode') == <?php echo \NF\NeoFrag\Core\Output::WIDGETS ?>){
			return;
		}

		var mode = <?php echo $this->output->live_editor() ?>;
		$('.live-editor-mode.active').each(function(){
			mode += $(this).data('mode');
		});

		$('input[type="hidden"][name="live_editor"]').val(mode);
		$('form[target="live-editor-iframe"]').submit();
	});

	$('#modules-links-collapse').on('click', '.dropdown-menu > a', function(){
		$('#live-editor-map').html('<?php echo icon('fas fa-spinner fa-spin').' '.$this->lang('Chargement en cours...') ?>');
		$('form[target="live-editor-iframe"]').prop('action', $(this).attr('href')).submit();
		$('.dropdown-menu').removeClass('show');
		$('.nav-item.dropdown').removeClass('show');
		return false;
	});

	/* Styles Overview */
	$('body').on('click', '.live-editor-overview:not(.active)', function(){
		var $element = $(this).parents('.modal:first').data('element');
		$element.switchClass($element.data('previous-style'), $(this).data('style'), 200);
		$element.data('previous-style', $(this).data('style'));
		$('.live-editor-overview').removeClass('active');
		$(this).addClass('active');
	});

	$('.live-editor-iframe iframe').on('load', function(){
		var $iframe = $(this).contents();

		$('#live-editor-map').html($iframe.find('#live_editor').data('module-title'));

		$iframe.on('mouseover', '.widget, .module', function(){
			if ($widgets.hasClass('active') && !$(this).find('.widget-hover').length){
				$iframe.find('.widget-hover').remove();
				$('	<div class="widget-hover">\
						<div class="widget-hover-content">\
							<h5>'+($(this).hasClass('module') ? '<b><?php echo $this->lang('Module') ?></b> ' : '')+$(this).data('title')+'</h5>\
							<div class="btn-group" role="group">\
								'+(!$(this).hasClass('module') ? '<button type="button" class="btn btn-info live-editor-style" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $this->lang('Apparence') ?>"><?php echo icon('fas fa-paint-brush') ?></button>' : '')+'\
								<button type="button" class="btn btn-warning live-editor-setting" data-toggle="tooltip" data-placement="bottom" data-container="body" title="<?php echo $this->lang('Configurer') ?>"><?php echo icon('fas fa-cogs') ?></button>\
								<button type="button" class="btn btn-danger live-editor-delete" data-toggle="tooltip" data-placement="bottom" data-container="body" title="<?php echo $this->lang('Supprimer') ?>"><?php echo icon('far fa-trash-alt') ?></button>\
							</div>\
						</div>\
					</div>').prependTo(this).fadeTo('fast', 1);
			}
		});

		$iframe.on('mouseleave', '.widget-hover', function(){
			$(this).remove();
		});

		$iframe.on('click', 'a', function(){
			var href = $(this).attr('href');

			if (href.match(/<?php echo str_replace('/', '\/', url()) ?>(?!(admin|live-editor|#))/)){
				$('#live-editor-map').html('<?php echo icon('fas fa-spinner fa-spin').' '.$this->lang('Chargement en cours...') ?>');
				$('form[target="live-editor-iframe"]').prop('action', href).submit();
			}

			return false;
		});

		/* Zone Fork */
		$iframe.on('click', '.live-editor-zone .live-editor-fork', function(){
			var $this = $(this);
			var fork = function(){
				$('.live-editor-save').show();

				var $zone = $this.parents('[data-disposition-id]:first');

				$.post('<?php echo url('admin/ajax/live-editor/zone-fork') ?>', {
					disposition_id: $zone.data('disposition-id'),
					url: $iframe[0].location.pathname,
					live_editor: $('input[type="hidden"][name="live_editor"]').val()
				}, function(data){
					if ($(data).find('.live-editor-widget.module').length){
						$('form[target="live-editor-iframe"]').submit();
					}
					else {
						$zone.replaceWith(data);
					}
				}).always(function(){
					$('.live-editor-save').hide();
				});
			};

			if ($this.data('enabled')){
				modal_fork(fork);
			}
			else {
				fork();
			}
		});

		/* Row Add */
		$iframe.on('click', '.live-editor-add-row', function(){
			var $this = $(this).parents('[data-disposition-id]:first');
			$('.live-editor-save').show();

			$.post('<?php echo url('admin/ajax/live-editor/row-add') ?>', {
				disposition_id: $this.data('disposition-id'),
				live_editor: $('input[type="hidden"][name="live_editor"]').val()
			}, function(data){
				var $rows_button = $('.live-editor-mode[data-mode="<?php echo \NF\NeoFrag\Core\Output::ROWS ?>"]');

				if (!$rows_button.hasClass('active')){
					$rows_button.trigger('click');
				}
				else {
					$this.append(data);
				}
			}).always(function(){
				$('.live-editor-save').hide();
			});
		});

		/* Row Move */
		$iframe.find('[data-disposition-id]').sortable({
			axis: 'y',
			containment: 'parent',
			cursor: 'move',
			intersect: 'pointer',
			items: '> .live-editor-row',
			opacity: 0.6,
			placeholder: 'live-editor-placeholder',
			revert: true,
			start: function(event, ui){
				ui.placeholder.css('height', ui.item.height());
			},
			update: function(event, ui){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/row-move') ?>', {
					disposition_id: $(this).data('disposition-id'),
					row_id: ui.item.find('.row:first').data('row-id'),
					position: $(this).find('.live-editor-row').index(ui.item)
				}).always(function(){
					$('.live-editor-save').hide();
				});
			}
		});

		/* Row Style */
		$iframe.on('click', '.live-editor-row-header .live-editor-style', function(){
			var $this = $(this);
			var $row = $this.parents('.live-editor-row-header:first').next('.row');

			modal_style('<?php echo $this->lang('Apparence de la ligne') ?>', $row, '.live-editor-styles-row', function(style){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/row-style') ?>', {
					disposition_id: $this.parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $row.data('row-id'),
					style: style
				}).always(function(){
					$('.live-editor-save').hide();
				});
			});
		});

		/* Row Delete */
		$iframe.on('click', '.live-editor-row-header .live-editor-delete', function(){
			var $this = $(this);

			modal_delete('<?php echo $this->lang('Êtes-vous sûr(e) de vouloir supprimer cette <b>ligne</b> ?<br />Toutes les <b>colonnes</b> et <b>widgets</b> contenus seront également supprimés.') ?>', function(){
				var $row = $this.parents('.live-editor-row-header:first').next('.row');

				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/row-delete') ?>', {
					disposition_id: $this.parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $row.data('row-id')
				}, function(){
					$row.parents('.live-editor-row:first').remove();
				}).always(function(){
					$('.live-editor-save').hide();
				});
			});
		});

		/* Col Add */
		$iframe.on('click', '.live-editor-add-col', function(){
			var $row = $(this).parents('.live-editor-row-header:first').next('[data-row-id]:first');
			$('.live-editor-save').show();

			$.post('<?php echo url('admin/ajax/live-editor/col-add') ?>', {
				disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
				row_id: $row.data('row-id'),
				live_editor: $('input[type="hidden"][name="live_editor"]').val()
			}, function(data){
				var $cols_button = $('.live-editor-mode[data-mode="<?php echo \NF\NeoFrag\Core\Output::COLS ?>"]');

				if (!$cols_button.hasClass('active')){
					$cols_button.trigger('click');
				}
				else {
					$row.append(data);
				}
			}).always(function(){
				$('.live-editor-save').hide();
			});
		});

		/* Col Move */
		$iframe.find('[data-row-id]').sortable({
			axis: 'x',
			containment: 'parent',
			cursor: 'move',
			intersect: 'pointer',
			items: '> [data-col-id]',
			opacity: 0.6,
			placeholder: 'live-editor-placeholder',
			revert: true,
			start: function(event, ui){
				if (match = ui.item.prop('class').match(/(col-\d{1,2})/)){
					ui.placeholder.addClass(match[1]);
					ui.placeholder.css('height', ui.item.height());
				}
			},
			update: function(event, ui){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/col-move') ?>', {
					disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $(this).data('row-id'),
					col_id: ui.item.data('col-id'),
					position: $(this).find('[data-col-id]').index(ui.item)
				}).always(function(){
					$('.live-editor-save').hide();
				});
			}
		});

		/* Col Size */
		$iframe.on('click', '.live-editor-col .live-editor-size', function(){
			var $col = $(this).parents('[data-col-id]:first');
			var size;
			var old_size = size = 12;

			if (match = $col.prop('class').match(/col-lg-(\d{1,2})/)){
				old_size = parseInt(match[1]);
			}

			size = Math.max(1, Math.min(12, old_size+parseInt($(this).data('size'))));

			if (size != old_size){
				$col.switchClass('col-lg-'+old_size, 'col-lg-'+size, 200);

				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/col-size') ?>', {
					disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $(this).parents('[data-row-id]:first').data('row-id'),
					col_id: $col.data('col-id'),
					size: size
				}).always(function(){
					$('.live-editor-save').hide();
				});
			}
		});

		/* Col Delete */
		$iframe.on('click', '.live-editor-col > .btn-group > .live-editor-delete', function(){
			var $this = $(this);
			var $col  = $(this).parents('[data-col-id]:first');

			modal_delete('<?php echo $this->lang('Êtes-vous sûr(e) de vouloir supprimer cette <b>colonne</b> ?<br />Tous les <b>widgets</b> contenus seront également supprimés.') ?>', function(){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/col-delete') ?>', {
					disposition_id: $this.parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $this.parents('[data-row-id]:first').data('row-id'),
					col_id: $col.data('col-id')
				}, function(){
					$col.remove();
				}).always(function(){
					$('.live-editor-save').hide();
				});
			});
		});

		/* Widget Add */
		$iframe.on('click', '.live-editor-add-widget', function(){
			var $col  = $(this).parents('[data-col-id]:first');
			var data    = {
				disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
				row_id: $(this).parents('[data-row-id]:first').data('row-id'),
				col_id: $col.data('col-id'),
				widget_id: -1
			};

			$.post('<?php echo url('admin/ajax/live-editor/widget-settings') ?>', data, function(html){
				modal_settings('<?php echo $this->lang('Nouveau Widget') ?>', html, function(settings){
					$.extend(data, settings);
					$.extend(data, {
						live_editor: $('input[type="hidden"][name="live_editor"]').val()
					});

					$('.live-editor-save').show();

					$.post('<?php echo url('admin/ajax/live-editor/widget-add') ?>', data, function(data){
						if (settings.widget == 'module'){
							$('form[target="live-editor-iframe"]').submit();
						}
						else {
							$col.find('.live-editor-col:first').append(data);
						}
					}).always(function(){
						$('.live-editor-save').hide();
					});
				});
			});
		});

		/* Widget Move */
		$iframe.find('[data-col-id]').sortable({
			axis: 'y',
			containment: 'parent',
			cursor: 'move',
			intersect: 'pointer',
			items: '[data-widget-id]',
			opacity: 0.6,
			placeholder: 'live-editor-placeholder',
			revert: true,
			start: function(event, ui){
				ui.placeholder.css('height', ui.item.height());
			},
			update: function(event, ui){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/widget-move') ?>', {
					disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
					row_id: $(this).parents('[data-row-id]:first').data('row-id'),
					col_id: $(this).data('col-id'),
					widget_id: ui.item.data('widget-id'),
					position: $(this).find('[data-widget-id]').index(ui.item)
				}).always(function(){
					$('.live-editor-save').hide();
				});
			}
		});

		/* Widget Style */
		$iframe.on('click', '.live-editor-widget .live-editor-style', function(){
			var $widget = $(this).parents('[data-widget-id]:first');
			var data    = {
				disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
				row_id: $(this).parents('[data-row-id]:first').data('row-id'),
				col_id: $(this).parents('[data-col-id]:first').data('col-id'),
				widget_id: $widget.data('widget-id')
			};

			modal_style('<?php echo $this->lang('Apparence du Widget') ?>', $widget.children('.card'), '.live-editor-styles-widget', function(style){
				$.extend(data, {
					style: style
				});

				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/widget-style') ?>', data).always(function(){
					$('.live-editor-save').hide();
				});
			});
		});

		/* Widget Settings */
		$iframe.on('click', '.live-editor-widget .live-editor-setting', function(){
			var $widget = $(this).parents('[data-widget-id]:first');
			var data    = {
				disposition_id: $(this).parents('[data-disposition-id]:first').data('disposition-id'),
				row_id: $(this).parents('[data-row-id]:first').data('row-id'),
				col_id: $(this).parents('[data-col-id]:first').data('col-id'),
				widget_id: $widget.data('widget-id')
			};

			$.post('<?php echo url('admin/ajax/live-editor/widget-settings') ?>', data, function(html){
				modal_settings('<?php echo $this->lang('Configuration du Widget') ?>', html, function(settings){
					$.extend(data, settings);

					$('.live-editor-save').show();

					$.post('<?php echo url('admin/ajax/live-editor/widget-update') ?>', data, function(data){
						if (settings.widget == 'module'){
							$('form[target="live-editor-iframe"]').submit();
						}
						else {
							$widget.replaceWith(data);
						}
					}).always(function(){
						$('.live-editor-save').hide();
					});
				});
			});
		});

		/* Widget Delete */
		$iframe.on('click', '.live-editor-widget .live-editor-delete', function(){
			var $this = $(this);
			var $widget = $this.parents('[data-widget-id]:first');

			//data doit être construit avant l'appel à la modal
			var data  = {
				disposition_id: $this.parents('[data-disposition-id]:first').data('disposition-id'),
				row_id: $this.parents('[data-row-id]:first').data('row-id'),
				col_id: $this.parents('[data-col-id]:first').data('col-id'),
				widget_id: $widget.data('widget-id')
			};

			modal_delete('<?php echo $this->lang('Êtes-vous sûr(e) de vouloir supprimer ce <b>widget</b> ?') ?>', function(){
				$('.live-editor-save').show();

				$.post('<?php echo url('admin/ajax/live-editor/widget-delete') ?>', data, function(){
					$widget.remove();
					$('.live-editor-save').hide();
				});
			});
		});
	});
});

$('[data-typer]').attr('data-typer', function(i, txt){
	var $typer = $(this),
		tot = txt.length,
		pauseMax = 300,
		pauseMin = 60,
		ch = 0;

	(function typeIt() {
		if (ch > tot) return;
		$typer.text(txt.substring(0, ch++));
		setTimeout(typeIt, ~~(Math.random() * (pauseMax - pauseMin + 1) + pauseMin));
	}());
});
