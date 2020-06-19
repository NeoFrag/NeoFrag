$(function(){
	$('[data-action] td').click(function(){
		var $access = $(this).parents('[data-action]:first');

		if (!$access.hasClass('info')){
			$.ajax({
				url: '<?php echo url('admin/ajax/access') ?>',
				type: 'POST',
				data: {
					'module': $('[name="module"]').val(),
					'type':   $('[name="type"]').val(),
					'id':     $('[name="id"]').val(),
					'action': $access.data('action')
				},
				success: function(data){
					var $row  = $('.module-access > .row:first');
					var $cols = $row.children('[class^="col-"]');

					if ($cols.length > 1){
						$cols.last().remove();
					}

					$cols.find('.info[data-action]').removeClass('info');

					$access.addClass('info');
					$row.append(data);
				}
			});
		}

		return false;
	});

	var update_access = function($btn, revoke){
		var data = {
			'module': $('[name="module"]').val(),
			'type':   $('[name="type"]').val(),
			'id':     $('[name="id"]').val(),
			'action': $('.info[data-action]').data('action')
		};

		var $table = $btn.parents('.table:first');

		if ($table.find('[data-group]').length){
			data['groups'] = {};

			$table.find('[data-group]').each(function(){
				data['groups'][$(this).data('group')] = $(this).find('.access-radio.success').length;
			});
		}
		else {
			var $tr = $btn.parents('tr:first');

			data['user'] = {};
			data['user'][$tr.find('[data-user-id]').data('user-id')] = typeof revoke == 'undefined' ? $tr.find('.access-radio.success').length : -1;
		}

		$.ajax({
			url: '<?php echo url('admin/ajax/access/update.json') ?>',
			type: 'POST',
			data: data,
			success: function(data){
				var $row  = $('.module-access > .row:first');
				var $cols = $row.children('[class^="col-"]');

				if (typeof data.details != 'undefined'){
					if ($cols.length > 1){
						$cols.last().remove();
					}

					$row.append(data.details);
				}

				if (typeof data.user_authorized != 'undefined' && typeof data.user_forced != 'undefined'){
					if (data.user_forced){
						$tr.find('.access-status').html('<a class="access-revoke" href="#" data-toggle="tooltip" title="<?php echo $this->lang('Remettre en automatique') ?>"><?php echo icon('fas fa-thumbtack') ?></a>');
					}
					else {
						$tr.find('.access-status').html('');
					}

					update_radio($tr.find('[data-class="'+(data.user_authorized ? 'success' : 'danger')+'"]'));
				}

				$cols.find('.info[data-action] .access-count').html(data.count);
			}
		});
	};

	var update_radio = function($btn){
		var color = $btn.data('class');

		if (!$btn.hasClass(color)){
			$btn.addClass(color).find('i').addClass('text-'+color).removeClass('text-muted').removeClass('fas fa-toggle-off').addClass('fas fa-toggle-on');
			$btn.parent().find('.access-radio').each(function(){
				if ($(this)[0] != $btn[0]){
					var color = $(this).data('class')
					$(this).removeClass(color).find('i').removeClass('text-'+color).addClass('text-muted').removeClass('fas fa-toggle-on').addClass('fas fa-toggle-off');
				}
			});

			return true;
		}

		return false;
	};

	$(document).on('click', '.access-radio', function(){
		if (update_radio($(this))){
			update_access($(this));
		}

		return false;
	});

	$(document).on('click', '.access-revoke', function(){
		update_access($(this), true);

		return false;
	});

	$(document).on('click', '[data-radio]', function(){
		var update = false;

		$(this).parents('.table:first').find('.access-radio[data-class="'+$(this).data('radio')+'"]').each(function(){
			update = update_radio($(this)) || update;
		});

		if (update){
			update_access($(this));
		}
	});

	$(document).on('click', '.access-users', function(){
		$.ajax({
			url: '<?php echo url('admin/ajax/access/users') ?>',
			type: 'POST',
			data: {
				'module': $('[name="module"]').val(),
				'type':   $('[name="type"]').val(),
				'id':     $('[name="id"]').val(),
				'action': $('.info[data-action]').data('action')
			},
			success: function(data){
				$(data).appendTo('body').modal().on('hidden.bs.modal', function(){
					$(this).remove();
				});
			}
		});

		return false;
	});

	$(document).on('click', '.access-reset', function(){
		$('	<div class="modal modal-access-reset fade" tabindex="-1" role="dialog">\
				<div class="modal-dialog">\
					<div class="modal-content">\
						<div class="modal-header">\
							<h5 class="modal-title"><?php echo $this->lang('Confirmation de réinitialisation des permissions') ?></h5>\
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $this->lang('Fermer') ?></span></button>\
						</div>\
						<div class="modal-body">\
							<?php echo $this->lang('Êtes-vous sûr(e) de vouloir réinitialiser les permissions ?') ?>\
						</div>\
						<div class="modal-footer">\
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang('Annuler') ?></button>\
							<button class="btn btn-info" data-module="'+$(this).data('module')+'" data-type="'+$(this).data('type')+'" data-id="'+$(this).data('id')+'"><?php echo $this->lang('Réinitialiser') ?></button>\
						</div>\
					</div>\
				</div>\
			</div>').appendTo('body').modal();

		return false;
	});

	$(document).on('click', '.modal-access-reset .btn-info', function(){
		$.ajax({
			url: '<?php echo url('admin/ajax/access/reset') ?>',
			type: 'POST',
			data: {
				'module': $(this).data('module'),
				'type':   $(this).data('type'),
				'id':     $(this).data('id')
			},
			success: function(data){
				window.location.reload();
			}
		});

		return false;
	});

	$('[data-action]:first td:first').trigger('click');
});
