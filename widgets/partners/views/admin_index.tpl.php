<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Options' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<input type="hidden" name="settings[id]" value="<?php echo isset($id) ? $id : unique_id() ?>" />
		<div class="form-group row">
			<label for="settings-display_number" class="col-3 col-form-label">Nombre par slider</label>
			<div class="col-2">
				<select class="form-control" name="settings[display_number]" id="settings-display_number">
				<?php foreach ([1, 2, 3, 4, 6] as $i): ?>
					<option<?php if (isset($display_number) && $display_number == $i) echo ' selected="selected"' ?>><?php echo $i ?></option>
				<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_style" class="col-3 col-form-label">Style du logo</label>
			<div class="col-4">
				<select class="form-control" name="settings[display_style]" id="settings-display_style">
					<option value="light"<?php if (!isset($display_style) || $display_style == 'light') echo ' selected="selected"' ?>>Logo clair</option>
					<option value="dark"<?php if (isset($display_style) && $display_style == 'dark') echo ' selected="selected"' ?>>Logo foncé</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="settings-display_height" class="col-3 col-form-label">Hauteur maximum</label>
			<div class="col-3">
				<div class="input-group">
					<input type="number" class="form-control" name="settings[display_height]" id="settings-display_height" value="<?php echo isset($display_height) ? $display_height : '140' ?>" />
					<div class="input-group-append">
						<span class="input-group-text">px</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
