<div class="row">
<?php foreach ($this->addons->get_themes() as $theme): ?>
	<div class="col-md-12 col-lg-4">
		<div class="thumbnail thumbnail-theme<?php if ($active = $theme->name == $this->config->nf_default_theme) echo ' panel-primary'; ?>" role="button" data-theme="<?php echo $theme->name; ?>" data-title="<?php echo $theme->get_title(); ?>">
			<div class="row">
				<div class="col-md-4 col-lg-12">
					<img src="<?php echo url($theme->thumbnail); ?>" class="img-responsive" alt="" />
				</div>
				<div class="col-md-8 col-lg-12">
					<div class="caption">
						<h3>
							<?php echo $theme->get_title(); ?>
							<small><?php echo $theme->version; ?></small>
							<span class="pull-right">
							<?php if (($checker = $theme->controller('admin')) && $checker->has_method('index')): ?>
								<a class="btn btn-outline btn-info btn-xs" href="<?php echo url('admin/addons/theme/'.$theme->name); ?>" title="<?php echo $this->lang('personalize'); ?>" data-toggle="tooltip"><?php echo icon('fa-paint-brush'); ?></a>
							<?php endif; ?>
							<button class="btn btn-outline btn-warning btn-xs" title="<?php echo $this->lang('reinstall_to_default'); ?>" data-toggle="tooltip"><?php echo icon('fa-refresh'); ?></button>
							<?php if ($theme->is_removable()) echo $this->button_delete('admin/addons/delete/theme/'.$theme->name); ?>
							</span>
						</h3>
						<p><?php echo $theme->lang($theme->description, NULL); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>
<div class="modal modal-theme fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang('close'); ?></span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang('cancel'); ?></button>
			</div>
		</div>
	</div>
</div>