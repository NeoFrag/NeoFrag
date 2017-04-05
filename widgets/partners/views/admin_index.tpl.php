<div role="tabpanel">
	<ul id="partners-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#partners-options" aria-controls="partners-options" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="partners-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<input type="hidden" name="settings[id]" value="<?php echo isset($data['id']) ? $data['id'] : unique_id(); ?>" />
				<div class="form-group">
					<label for="settings-display_number" class="col-sm-5 control-label">Nombre de partenaires par slider</label>
					<div class="col-sm-2">
						<select class="form-control" name="settings[display_number]" id="settings-display_number">
						<?php foreach ([1, 2, 3, 4, 6] as $i): ?>
							<option<?php if (isset($data['display_number']) && $data['display_number'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
						<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-display_style" class="col-sm-5 control-label">Style du logo à afficher</label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[display_style]" id="settings-display_style">
							<option value="light"<?php if (!isset($data['display_style']) || $data['display_style'] == 'light') echo ' selected="selected"'; ?>>Logo clair</option>
							<option value="dark"<?php if (isset($data['display_style']) && $data['display_style'] == 'dark') echo ' selected="selected"'; ?>>Logo foncé</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-display_height" class="col-sm-5 control-label">Hauteur maximum du widget</label>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="number" class="form-control" name="settings[display_height]" id="settings-display_height" value="<?php echo isset($data['display_height']) ? $data['display_height'] : '140'; ?>" />
							<div class="input-group-addon">px</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>