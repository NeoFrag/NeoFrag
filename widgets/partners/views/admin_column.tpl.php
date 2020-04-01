<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-options-tab" data-toggle="pill" href="#pills-options" role="tab" aria-controls="pills-options" aria-selected="true"><?php echo icon('fas fa-cogs').' Options' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
		<div class="form-group row">
			<label for="settings-display_style" class="col-3 col-form-label">Style des logos</label>
			<div class="col-4">
				<select class="form-control" name="settings[display_style]" id="settings-display_style">
					<option value="light"<?php if (!isset($display_style) || $display_style == 'light') echo ' selected="selected"' ?>>Logo clair</option>
					<option value="dark"<?php if (isset($display_style) && $display_style == 'dark') echo ' selected="selected"' ?>>Logo foncé</option>
				</select>
			</div>
		</div>
	</div>
</div>
