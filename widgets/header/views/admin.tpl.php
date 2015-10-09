<div role="tabpanel">
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs').' '.i18n('options'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="navigation-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-align" class="col-sm-3 control-label"><?php echo i18n('alignment'); ?></label>
					<div class="col-sm-3">
						<select class="form-control" name="settings[align]" id="settings-align">
							<option value="text-left"<?php if ($data['align'] == 'text-left') echo ' selected="selected"'; ?>><?php echo i18n('left'); ?></option>
							<option value="text-center"<?php if ($data['align'] == 'text-center') echo ' selected="selected"'; ?>><?php echo i18n('center'); ?></option>
							<option value="text-right"<?php if ($data['align'] == 'text-right') echo ' selected="selected"'; ?>><?php echo i18n('right'); ?></option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-title" class="col-sm-3 control-label"><?php echo i18n('website_title'); ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[title]" value="<?php echo $data['title']; ?>" id="settings-title" placeholder="<?php echo i18n('default_title'); ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-addon"><?php echo icon('fa-paint-brush'); ?></div>
							<input type="text" class="form-control" name="settings[color-title]" value="<?php echo $data['color-title']; ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-description" class="col-sm-3 control-label"><?php echo i18n('description'); ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[description]" value="<?php echo $data['description']; ?>" id="settings-description" placeholder="<?php echo i18n('default_description'); ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-addon"><?php echo icon('fa-paint-brush'); ?></div>
							<input type="text" class="form-control" name="settings[color-description]" value="<?php echo $data['color-description']; ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>