<div class="addons-panel-body" data-type="authenticator">
	<?php foreach ($data['authenticators'] as $authenticator): ?>
	<div class="addon-item<?php echo ($enabled = $authenticator->is_enabled()) ? ' active' : ''; ?>" data-name="<?php echo $authenticator->name; ?>">
		<div class="item-status">
			<div class="item-status-icon">
				<?php echo $enabled ? '<span data-toggle="tooltip" title="Activé">'.icon('fa-check').'</span>' : '<span data-toggle="tooltip" title="Désactivé">'.icon('fa-ban').'</span>'; ?>
			</div>
			<div class="item-status-switch">
				<?php echo $enabled ? '<a href="#" data-toggle="tooltip" title="Désactiver l\'authentificateur">'.icon('fa-toggle-on text-green').'</a>' : '<a href="#" data-toggle="tooltip" title="Activer l\'authentificateur">'.icon('fa-toggle-off text-muted').'</a>'; ?>
			</div>
			<div class="item-name">
				<?php echo icon($authenticator->icon).' <b>'.$authenticator->title.'</b>'; ?>
			</div>
		</div>
		<div class="item-action">
			<?php
				if (!$enabled && !$authenticator->is_setup()) echo $this->button()
																		->tooltip('Configuration manquante')
																		->icon('fa-warning text-danger')
																		->color('link');
				echo $this	->button()
							->tooltip('Configurer')
							->icon('fa-wrench')
							->color('warning')
							->compact()
							->outline().
					'&nbsp;'.
					$this	->button_sort($authenticator->name, 'admin/ajax/addons/authenticator/sort', '.addons-panel-body', '.addon-item')
							->color('default');
			?>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<div class="modal modal-authenticator fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang('close'); ?></span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang('cancel'); ?></button>
				<button type="button" class="btn btn-success"><?php echo $this->lang('save'); ?></button>
			</div>
		</div>
	</div>
</div>