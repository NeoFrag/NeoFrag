<div class="addons-panel-body" data-type="module">
	<?php foreach ($this->addons->get_modules(TRUE) as $module): ?>
	<?php
		$settings      = method_exists($module, 'settings');
		$access        = $module->get_permissions('default');
		$deactivatable = $module->is_deactivatable();
		$removable     = $module->is_removable();

		if (!$settings && !$access && !$deactivatable & !$removable)
		{
			continue;
		}
	?>
	<div class="addon-item<?php echo $this->addons->is_enabled($module->name, 'module') ? ' active' : ''; ?>" data-name="<?php echo $module->name; ?>">
		<div class="item-status">
			<div class="item-status-icon">
				<?php echo $this->addons->is_enabled($module->name, 'module') ? '<span data-toggle="tooltip" title="Activé">'.icon('fa-check').'</span>' : '<span data-toggle="tooltip" title="Désactivé">'.icon('fa-ban').'</span>'; ?>
			</div>
			<div class="item-status-switch">
				<?php if ($deactivatable) echo $this->addons->is_enabled($module->name, 'module') ? '<a href="#" data-toggle="tooltip" title="Désactiver le module">'.icon('fa-toggle-on text-green').'</a>' : '<a href="#" data-toggle="tooltip" title="Activer le module">'.icon('fa-toggle-off text-muted').'</a>'; ?>
			</div>
			<div class="item-name">
				<?php echo icon($module->icon).' <b>'.$module->get_title().'</b><br />'; ?>
				<small><?php echo $module->description; ?></small>
			</div>
		</div>
		<div class="item-action">
			<?php if ($settings) echo button('admin/addons/module/'.$module->name.'.html', 'fa-wrench', 'Configurer', 'warning'); ?>
			<?php if ($access) echo button_access($module->name); ?>
			<?php if (0 && $removable) echo button_delete('admin/addons/delete/module/'.$module->name.'.html'); ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>