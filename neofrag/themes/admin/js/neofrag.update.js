$(function(){
	var progressBar = [];
	
	$('#modal-update .progress-bar').each(function(i){
		var $progress = $(this);
		var start = 0;
		$.each(String($(this).data('step')).split(','), function(key, value){
			value = parseInt(value);
			progressBar.push([$progress.data('index', i), start, value]);
			start += value;
		});
	});

	$('#modal-update .btn-primary').click(function(){
		var $btn = $(this).button('loading');
		$('#modal-update .step:eq(0)').addClass('active');

		$('#modal-update').on('hidden.bs.modal', function(){
			$btn.button('reset');
			$('#modal-update .step').removeClass('active');
			$('#modal-update .progress-bar').data('value', 0).css('width', 0);
		});

		$.ajax({
			url: '<?php echo url('admin/ajax/monitoring/update.json'); ?>',
			cache: false,
			xhr: function(){
				var xhr = new window.XMLHttpRequest();
				xhr.addEventListener('progress', function(){
					var data = xhr.response.split(';').map(function(value){
						return JSON.parse(value.trim());
					});
					
					$.each(data, function(key, data){
						var $progressBar = progressBar[data[0]][0];
						var value = $progressBar.data('value');
						var steps = String($progressBar.data('step')).split(',');
						var pourcent = Math.ceil(progressBar[data[0]][1] + (data[1] * progressBar[data[0]][2] / 100));

						if (typeof value == 'undefined' || value < pourcent){
							$progressBar.addClass('progress-bar-striped active').data('value', pourcent).css('width', pourcent+'%');
							if (pourcent == 100){
								$progressBar.removeClass('progress-bar-striped active');
								$('#modal-update .step:eq('+($progressBar.data('index')+1)+')').addClass('active');
							}
						}
					});
				}, false);
				return xhr;
			},
			success: function(){
				$('.module-monitoring .refresh').trigger('click');
				setTimeout(function(){
					$('#modal-update').modal('hide');
					notify('Mise à jour effectuée avec succès');
				}, 1000);
			}
		});

		return false;
	});
});