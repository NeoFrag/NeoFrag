$(function(){
	$('.panel-notifications .panel-body').mCustomScrollbar({
		theme: 'dark',
		setHeight: 257
	});

	var printSize = function(bytes, decimals, callback){
		var sz = ('BKMGTP').split('');
		var factor = Math.floor((String(bytes).length - 1) / 3);
		var unit = (typeof sz[factor] != 'undefined' ? sz[factor] : '')+'o';
		return (bytes / Math.pow(1024, factor)).toFixed(typeof decimals != 'undefined' ? decimals : 2)+'<small>'+unit+'</small>';
	};
	
	var loading = false;
	
	var refresh = function(refresh){
		if (loading) {
			return;
		}
		
		loading = true;
		$('.knob').val(0).trigger('change');
		$('.module-monitoring .refresh fa').addClass('fa-spin');
		$('#storage-pourcent, #monitoring-text').html('&nbsp')
		$('#storage-total, #storage-free, #storage-database, #storage-files, #storage-used, #monitoring-danger, #monitoring-warning, #monitoring-info').html('<?php echo icon('fa-spinner fa-spin'); ?>');
		$('.table-notifications').html('');
		$('.panel-infos .fa.text-green, .panel-infos .fa.text-danger').addClass('fa-spinner fa-spin').removeClass('fa-check-square text-green fa-exclamation-triangle text-danger');
		$('.panel-infos [data-label]').each(function(){
			$(this).html($(this).data('label'));
		});
		$('.panel-monitoring').addClass('bg-gray').removeClass('bg-red bg-orange bg-green');
		$('.monitoring-icon-status').removeClass('beat-fast beat-medium beat-slow');
		$('#tree').treeview('remove');

		$.post('<?php echo url('admin/ajax/monitoring.json'); ?>', {refresh: typeof refresh != 'undefined' && refresh ? refresh : 0}, function(data){
			var used     = data.storage.total - data.storage.free;
			var pourcent = Math.ceil(used / data.storage.total * 100);
			
			$('.knob').val(pourcent).trigger('change').trigger('configure', {
				fgColor: pourcent >= 90 ? '#d9534f' : (pourcent >= 75 ? '#f0ad4e' : '#25C7F0')
			});
			
			$.each(data.storage, function(key, value){
				$('#storage-'+key).html(printSize(value));
			});

			$('#storage-used').html(printSize(used));
			$('#storage-pourcent').html('Utilisé ('+pourcent+' %)');
			
			var notifications = '';
			var count = {
				danger: 0,
				warning: 0,
				info: 0
			};

			$.each(data.notifications, function(i, notification){
				notifications += '	<tr>\
										<td class="col-lg-2"><span class="label label-'+notification[1]+'">'+(notification[1] == 'danger' ? '<?php echo icon('fa-bug'); ?> Erreur' : (notification[1] == 'warning' ? '<?php echo icon('fa-flash'); ?> Anomalie' : '<?php echo icon('fa-exclamation-circle'); ?> Conseil'))+'</span></td>\
										<td class="vcenter">'+notification[0]+'</td>\
									</tr>';
				count[notification[1]]++;
			});

			$('.table-notifications').html('<tbody>'+notifications+'</tbody>');

			$.each(count, function(key, value){
				$('#monitoring-'+key).html(value);
			});

			$('#monitoring-text').html(count.danger ? 'Le bateau coule !' : (count.warning ? 'Iceberg, droit devant !' : 'Rien à signaler capitaine !'));
			$('.panel-monitoring').removeClass('bg-gray').addClass(count.danger ? 'bg-red' : (count.warning ? 'bg-orange' : 'bg-green'));
			$('.monitoring-icon-status').addClass(count.danger ? 'beat-fast' : (count.warning ? 'beat-medium' : 'beat-slow'));

			$('#tree').treeview({
				data: data.files,
				collapseIcon: 'fa fa-folder-open-o',
				expandIcon: 'fa fa-folder-o',
				emptyIcon: 'fa fa-file-o',
				showTags: true,
				levels: 1
			});

			$.each(data.server, function(key, value){
				var result = value;

				if (Array.isArray(result)){
					result = value[0];
					$('#server-'+key+' > span').attr('data-label', $('#server-'+key+' > span').html()).html(value[1]);
				}

				$('#server-'+key+' .fa').removeClass('fa-spinner fa-spin').addClass(result ? 'fa-check-square text-green' : 'fa-exclamation-triangle text-danger');
			});

			loading = false;
			$('.module-monitoring .refresh .fa').removeClass('fa-spin');
		});
	};

	$('.module-monitoring .refresh').click(function(){
		return refresh(true);
	});

	$('#modal-backup .btn-primary').click(function(){
		var $btn = $(this).button('loading');
		$('#modal-backup .step:eq(0)').addClass('active');

		$('#modal-backup').on('hidden.bs.modal', function(){
			$btn.button('reset');
			$('#modal-backup .step').removeClass('active');
			$('#modal-backup .progress-bar').data('value', 0).css('width', 0);
		});

		$.ajax({
			url: '<?php echo url('admin/ajax/monitoring/backup.json'); ?>',
			cache: false,
			xhr: function(){
				var xhr = new window.XMLHttpRequest();
				xhr.addEventListener('progress', function(){
					var data = xhr.response.split(';').map(function(value){
						return JSON.parse(value.trim());
					});
					
					$.each(data, function(key, data){
						var $progressBar = $('#modal-backup .progress-bar:eq('+data[0]+')');
						var value = $progressBar.data('value');

						if (typeof value == 'undefined' || value < data[1]){
							$progressBar.addClass('progress-bar-striped active').data('value', data[1]).css('width', data[1]+'%');

							if (data[1] == 100){
								$progressBar.removeClass('progress-bar-striped active');
								$('#modal-backup .step:eq('+(data[0]+1)+')').addClass('active');
							}
						}
					});
				}, false);
				return xhr;
			},
			success: function(){
				setTimeout(function(){
					$('#modal-backup').modal('hide');
					notify('Sauvegarde réalisée dans le dossier <b>backups</b> de votre FTP');
				}, 1000);
			}
		});

		return false;
	});

	refresh();
});