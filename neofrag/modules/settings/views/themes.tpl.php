<?php foreach ($data['themes'] as $theme): ?>
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="thumbnail<?php if ($active = $theme->name == $NeoFrag->config->nf_default_theme) echo ' panel-primary'; ?>" role="button" data-theme="<?php echo $theme->name; ?>" data-title="<?php echo $theme->get_title(); ?>">
		<img src="<?php echo url($theme->thumbnail); ?>" alt="" />
		<div class="caption">
			<h3>
				<?php echo $theme->get_title(); ?>
				<?php if ($theme->name != 'default'): ?><small><?php echo $theme->version; ?></small><?php endif; ?>
				<span class="pull-right">
				<?php if (!is_null($checker = $theme->load->controller('admin')) && method_exists($checker, 'index')): ?>
					<a class="btn btn-outline btn-info btn-xs" href="<?php echo url('admin/settings/themes/'.$theme->name.'.html'); ?>" title="<?php echo i18n('personalize'); ?>" data-toggle="tooltip"><?php echo icon('fa-paint-brush'); ?></a>
				<?php endif; ?>
				<button class="btn btn-outline btn-warning btn-xs" title="<?php echo i18n('reinstall_to_default'); ?>" data-toggle="tooltip"><?php echo icon('fa-refresh'); ?></button>
				<?php if ($theme->name != 'default'): ?>
					<button class="btn btn-outline btn-danger btn-xs<?php if ($active) echo ' disabled'; ?>" title="<?php echo i18n('remove'); ?>" data-toggle="tooltip"><?php echo icon('fa-close'); ?></button>
				<?php endif; ?>
				</span>
			</h3>
			<p><?php echo $theme->load->lang($theme->description, NULL); ?></p>
		</div>
	</div>
</div>
<?php endforeach; ?>
<div class="modal modal-theme-install fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo i18n('close'); ?></span></button>
				<h4 class="modal-title"><?php echo i18n('install_theme'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="modal-theme-install-upload">
					<p><?php echo icon('fa-download').' '.i18n('upload_zip_file', file_upload_max_size() / 1024 / 1024); ?></p>
					<input type="file" accept=".zip" />
				</div>
				<div class="modal-theme-install-progress progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo i18n('cancel'); ?></button>
				<button type="button" class="btn btn-info disabled"><?php echo i18n('install'); ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-theme fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo i18n('close'); ?></span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo i18n('cancel'); ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('.modal-theme-install input[type="file"]').change(function(){
			if ($(this).val()){
				$('.modal-theme-install .btn-info').removeClass('disabled');
			}
			else{
				$('.modal-theme-install .btn-info').addClass('disabled');
			}
		});
		
		$('.modal-theme-install').on('show.bs.modal', function(){
			$('.modal-theme-install .btn-info').addClass('disabled');
			$('.modal-theme-install .modal-theme-install-upload').show();
			$('.modal-theme-install .modal-theme-install-progress').hide();
			$('.modal-theme-install .progress-bar').removeClass('progress-bar-danger').html('');
		});
		
		$('.modal-theme-install .btn-info').click(function(){
			var formData = new FormData();
			formData.append('theme', $('.modal-theme-install input[type="file"]')[0].files[0]);
			
			$('.modal-theme-install input[type="file"]').val('');

			$('.modal-theme-install .modal-theme-install-upload').hide();
			$('.modal-theme-install .modal-theme-install-progress').show();
			
			$.ajax({
				url: '<?php echo url('admin/ajax/settings/themes/install.json'); ?>',
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				xhr: function(){
					var xhr = $.ajaxSettings.xhr();
					
					xhr.upload.addEventListener('progress', function(e){
						if (e.lengthComputable){
							var pourcent = Math.ceil(e.loaded / e.total * 100);
							$('.modal-theme-install .progress-bar')
								.html(pourcent+'%')
								.css('width', pourcent+'%')
								.attr('aria-valuenow', pourcent);
								
							if (pourcent == 100){
								$('.modal-theme-install .progress-bar').html('<?php echo i18n('install_in_progress'); ?>');
							}
						}
					}, false);
					
					return xhr;
				},
				success: function(data){
					if (typeof data.success != 'undefined'){
						window.location.reload();
					}
					else if (typeof data.error != 'undefined'){
						$('.modal-theme-install .progress-bar').addClass('progress-bar-danger').html(data.error);
						$('.modal-theme-install .btn-info').addClass('disabled');
					}
				}
			});
		})

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
		$('.thumbnail').click(function(){
			if (!$(this).hasClass('panel-primary')){
				modal_theme('<?php echo i18n('theme_activation'); ?>', '<?php echo i18n('theme_activation_message'); ?>', '<button type="button" class="btn btn-info" data-theme="'+$(this).data('theme')+'"><?php echo i18n('activate'); ?></button>', function(){
					$.post('<?php echo url('admin/ajax/settings/themes/active.html'); ?>', {theme: $(this).data('theme')}, function(data){
						$('.thumbnail .btn-danger.disabled').removeClass('disabled');
						$('.thumbnail.panel-primary').removeClass('panel-primary');
						$('.thumbnail[data-theme="'+data+'"]').addClass('panel-primary').find('.btn-danger').addClass('disabled');
						$('.modal-theme-activation').modal('hide');
					});
				});
			}
			
			return false;
		});
		
		//Customization
		$('.thumbnail .btn-info').click(function(e){
			e.stopPropagation();
		});
		
		//Reset
		$('.thumbnail .btn-warning').click(function(e){
			e.stopPropagation();
			modal_theme('<?php echo i18n('reinstall_to_default'); ?>', '<?php echo i18n('theme_reinstallation_message'); ?>', '<button type="button" class="btn btn-warning" data-theme="'+$(this).parents('.thumbnail:first').data('theme')+'"><?php echo i18n('reinstall'); ?></button>', function(){
				$.post('<?php echo url('admin/ajax/settings/themes/reset.html'); ?>', {theme: $(this).data('theme')});
			});
		});
		
		//Delete
		$('.thumbnail .btn-danger').click(function(e){
			e.stopPropagation();
			if (!$(this).hasClass('disabled')){
				modal_theme('<?php echo i18n('theme_deletion'); ?>', '<?php echo i18n('theme_deletion_message'); ?>', '<button type="button" class="btn btn-danger" data-theme="'+$(this).parents('.thumbnail:first').data('theme')+'"><?php echo i18n('remove'); ?></button>', function(){
					$.post('<?php echo url('admin/ajax/settings/themes/delete.html'); ?>', {theme: $(this).data('theme')}, function(data){
						$('.thumbnail[data-theme="'+data+'"]').remove();
					});
				});
			}
		});
	});
</script>