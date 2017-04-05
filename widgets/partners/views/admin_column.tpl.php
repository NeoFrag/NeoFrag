<div role="tabpanel">
	<ul id="partners-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#partners-options" aria-controls="partners-options" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="partners-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-display_style" class="col-sm-5 control-label">Style des logos</label>
					<div class="col-sm-6">
						<select class="form-control" name="settings[display_style]" id="settings-display_style">
							<option value="light"<?php if (!isset($data['display_style']) || $data['display_style'] == 'light') echo ' selected="selected"'; ?>>Logo clair</option>
							<option value="dark"<?php if (isset($data['display_style']) && $data['display_style'] == 'dark') echo ' selected="selected"'; ?>>Logo foncé</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>