<div role="tabpanel">
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs').' '.$this->lang('options'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="navigation-options" class="tab-pane active" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-align" class="col-sm-3 control-label"><?php echo $this->lang('alignment'); ?></label>
					<div class="col-sm-3">
						<select class="form-control" name="settings[align]" id="settings-align">
							<option value="text-left"<?php if (isset($data['align']) && $data['align'] == 'text-left') echo ' selected="selected"'; ?>><?php echo $this->lang('left'); ?></option>
							<option value="text-center"<?php if (!isset($data['align']) || $data['align'] == 'text-center') echo ' selected="selected"'; ?>><?php echo $this->lang('center'); ?></option>
							<option value="text-right"<?php if (isset($data['align']) && $data['align'] == 'text-right') echo ' selected="selected"'; ?>><?php echo $this->lang('right'); ?></option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-title" class="col-sm-3 control-label"><?php echo $this->lang('website_title'); ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[title]" value="<?php if (isset($data['title'])) echo $data['title']; ?>" id="settings-title" placeholder="<?php echo $this->lang('default_title'); ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-addon"><?php echo icon('fa-paint-brush'); ?></div>
							<input type="text" class="form-control" name="settings[color-title]" value="<?php if (isset($data['title'])) echo $data['color-title']; ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="settings-description" class="col-sm-3 control-label"><?php echo $this->lang('description'); ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="settings[description]" value="<?php if (isset($data['title'])) echo $data['description']; ?>" id="settings-description" placeholder="<?php echo $this->lang('default_description'); ?>" />
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-addon"><?php echo icon('fa-paint-brush'); ?></div>
							<input type="text" class="form-control" name="settings[color-description]" value="<?php if (isset($data['title'])) echo $data['color-description']; ?>" placeholder="#000000" /><!-- //TODO color picker -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>